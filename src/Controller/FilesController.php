<?php
namespace Files\Controller;

use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Filter\Encrypt;
use Laminas\Mvc\Controller\AbstractActionController;

class FilesController extends AbstractActionController
{
    use AdapterAwareTrait;
    
    public $form;
    private $files;

    public function uploadAction()
    {
        $user = $this->currentUser();
        $uuid = $user->UUID;
        $this->form->path = './data/files/' . $uuid;
        $this->form->addInputFilter();
        
        $this->files = $this->files;
        
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
                
                $encrypt = new Encrypt(['adapter' => 'BlockCipher']);
                $encrypt->setKey('--super-secret-passphrase--');
                
                $this->files->BLOB = $encrypt->filter(file_get_contents($files['FILE']['tmp_name']));
                
                
                $this->files->create();
            }
        }

        // -- Return to previous screen --//
        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
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
}