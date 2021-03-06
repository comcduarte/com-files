<?php
namespace Files\Controller;

use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Settings\Model\SettingsModel;

/**
 * 
 * @author DuarteC
 *
 * @var \Files\Model\FilesModel $files
 */
class FilesController extends AbstractActionController
{
    use AdapterAwareTrait;
    
    public $form;
    private $files;

    public function uploadAction()
    {
        $this->form->addInputFilter();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            $this->form->setData($post);

            $request = $this->getRequest();
            if ($this->form->isValid()) {
                $files = $request->getFiles()->toArray();
                $post = $request->getPost()->toArray();
                
                $this->files->NAME = $files['FILE']['name'];
                $this->files->SIZE = $files['FILE']['size'];
                $this->files->TYPE = $files['FILE']['type'];
                $this->files->REFERENCE = $post['REFERENCE'];
                $this->files->BLOB = file_get_contents($files['FILE']['tmp_name']);
                
                $settings = new SettingsModel($this->adapter);
                $settings->read(['SETTING' => 'MAX_ALLOWED_PACKET']);
                
                if ($this->files->SIZE >= $settings->VALUE) {
                    $this->flashmessenger()->addErrorMessage('File exceeded Maximum File Size.');
                } else {
                    $this->files->create();
                }
            }
        }

        // -- Return to previous screen --//
        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }
    
    public function viewAction()
    {
        $this->layout('files_layout');
                
        $uuid = $this->params()->fromRoute('uuid', 0);
        if (!$uuid) {
            $this->flashmessenger()->addErrorMessage('Did not pass identifier.');
            
            // -- Return to previous screen --//
            $url = $this->getRequest()->getHeader('Referer')->getUri();
            return $this->redirect()->toUrl($url);
        }
        
        $this->files->read(['UUID' => $uuid]);
        
        
        
        $view = new ViewModel();
        $view->setVariable('data', $this->files->BLOB);
        $view->setVariable('TYPE', $this->files->TYPE);
        $view->setVariable('NAME', $this->files->NAME);
        $view->setVariable('SIZE', $this->files->SIZE);
        
        return $view;
    }
    
    public function deleteAction()
    {
        $view = new ViewModel();
        $view->setTemplate('base/delete');
        
        $this->form->init();
        $view->setVariable('referring_url', $_SERVER['HTTP_REFERER']);
        
        $primary_key = $this->getPrimaryKey();
        $this->files->read([$this->files->getPrimaryKey() => $primary_key]);
        $this->form->bind($this->files);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            
            if ($del == 'Yes') {
                $this->files->delete();
            }
            
            return $this->redirect()->toUrl($request->getPost('referring_url'));
        }
        
        
        $view->setVariables([
            'model', $this->files,
            'form' => $this->form,
            'primary_key' => $this->files->getPrimaryKey(),
        ]);
        return ($view);
    }
    
    public function getFiles()
    {
        return $this->files;
    }
    
    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    public function getPrimaryKey()
    {
        $primary_key = $this->params()->fromRoute(strtolower($this->files->getPrimaryKey()),0);
        if (!$primary_key) {
            $this->flashmessenger()->addErrorMessage("Unable to retrieve record. Value not passed.");
            
            $url = $this->getRequest()->getHeader('Referer')->getUri();
            return $this->redirect()->toUrl($url);
        }
        return $primary_key;
    }

}
