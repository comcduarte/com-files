<?php
namespace Files\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Files\Form\FilesUploadForm;

class Files extends AbstractHelper
{
    public $data;
    public $title;
    public $reference;
    public $form;
    public $primary_key = 'UUID';
    
    public function __invoke()
    {
        return $this;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setData($data) 
    {
        $this->data = $data;
        return $this;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * @var \Laminas\View\Helper\Partial $partialHelper
     * @return \Files\View\Helper\Files
     */
    public function render()
    {
        $form = new FilesUploadForm();
        $form->init();
        $form->get('REFERENCE')->setValue($this->getReference());
        
        $partialHelper = $this->view->plugin('partial');
        $params = [
            'title' => $this->getTitle(),
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
            ],
        ];
        
        echo $partialHelper('files', $params);
        return $this;
    }
    
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }
    
    public function getReference()
    {
        return $this->reference;
    }
    
}