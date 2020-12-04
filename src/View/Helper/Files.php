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
    
    public function getCard()
    {
        $html = "<div class='card mb-4'>";
        $html .= $this->getCardHeader();
        $html .= $this->getCardBody();
        $html .= $this->getCardFooter();
        $html .= "</div>";
        return $html;
    }
    
    public function getCardHeader()
    {
        $html = "";
        
        $form = new FilesUploadForm('FilesUploadForm');
        $form->init();
        $form->addInputFilter();
        
        $form->prepare();
        $form->setAttribute('action', $this->view->url('files/default', ['action' => 'upload']));
        
        $submit = $form->get('SUBMIT');
        $file = $form->get('FILE');
        $reference = $form->get('REFERENCE');
            $reference->setValue($this->reference);
        
        $html .= $this->view->form()->openTag($form);
        $html .= $this->view->FormHidden($form->get('REFERENCE'));
//         $html .= $this->view->formCollection($form);
        $html .= "<div class='input-group'>";
        $html .= "<div class='custom-file'>";
        $html .= $this->view->FormFile($file);
        $html .= "<label class='custom-file-label' for='inputGroupFile04'>Choose file</label>";
        $html .= "</div><div class='input-group-append'>";
        $html .= $this->view->FormSubmit($submit);
        $html .= "</div></div>";
        $html .= $this->view->form()->closeTag($form);
        
        
        $html .= "
            <div class='card-header d-flex justify-content-between'>
        		<div>
        			<b>$this->title</b>
        		</div>
        	</div>";
        return $html;
    }
    
    public function getCardBody()
    {
        $html = "<div class='card-body'>";
        if (sizeof($this->data) === 0) { 
            $html .= "No Records Retrieved.";
            $html .= "</div>";
            return $html;
        }
        
        $html .= "<table class='table table-striped'>";
        
        /****************************************
         * TABLE HEAD
         ****************************************/
        $html .= "<thead><tr>";
        $header = array_keys($this->data[0]);
        foreach ($header as $key) {
            if (strcmp($key, $this->primary_key) == 0 ) { continue; }
            $html .= "<th>$key</th>";
        }
        $html .= "</tr></thead>";
        
        /****************************************
         * TABLE BODY
         ****************************************/
        $html .= "<tbody>";
        foreach ($this->data as $record) {
            $html .= "<tr>";
            foreach ($record as $key => $value) {
                if (strcmp($key, $this->primary_key) == 0 ) { continue; }
                $html .= "<td>$value</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</tbody>";
        
        
        $html .= "</table>";
        
        $html .= "</div>";
        return $html;
    }
    
    public function getCardFooter()
    {
        $html = "";
        return $html;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    public function setData($data) 
    {
        $this->data = $data;
        return $this;
    }
    
    public function render()
    {
        echo $this->getCard();
        return $this;
    }
    
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }
    
}