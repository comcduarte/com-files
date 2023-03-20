<?php
namespace Files\View\Helper;

use Files\Form\FilesUploadForm;

class BoxFiles extends Files
{
    use Variables;
    
    public function __invoke()
    {
        $this->setVariable('primary_key', 'UUID');
        return $this;
    }
    
    public function setData($data)
    {
        $this->setVariable('data', $data);
        return $this;
    }
    
    public function getData()
    {
        return $this->getVariable('data');
    }
    
    public function setTitle($title)
    {
        $this->setVariable('title', $title);
        return $this;
    }
    
    public function getTitle()
    {
        return $this->getVariable('title');
    }
    
    public function setReference($reference)
    {
        $this->setVariable('reference', $reference);
        return $this;
    }
    
    public function getReference()
    {
        return $this->getVariable('reference');
    }
    
    public function render()
    {
        $form = new FilesUploadForm();
        $form->init();
        $form->get('REFERENCE')->setValue($this->getReference());
        $this->setVariable('form', $form);
        $this->setVariable('acl_service', $this->getAclService());
        
        $partialHelper = $this->view->plugin('partial');
        
        /**
        $params = [
            'title' => $this->getTitle(),
            'acl_service' => $this->getAclService(),
            'data' => $this->getData(),
            'primary_key' => $this->primary_key,
            'form' => $form,
            'params' => [
                [
                    'route' => 'files/default',
                    'action' => 'view',
                    'key' => 'UUID',
                    'label' => 'View',
                ],
                [
                    'route' => 'files/default',
                    'action' => 'delete',
                    'key' => 'UUID',
                    'label' => 'Delete',
                ],
            ],
        ]; */
        
        echo $partialHelper('boxfiles', $this->getVariables());
        return $this;
    }
}