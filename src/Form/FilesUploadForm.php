<?php
namespace Files\Form;

use Components\Form\Element\Uuid;
use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Submit;
use Laminas\InputFilter\InputFilter;

class FilesUploadForm extends Form
{
    public function init()
    {
        $this->add([
            'name' => 'UUID',
            'type' => Uuid::class,
            'attributes' => [
                'id' => 'UUID',
                'class' => 'form-control',
                'required' => 'true',
            ],
            'options' => [
                'label' => 'UUID',
            ],
        ],['priority' => 0]);
        
        $this->add([
            'name' => 'FILE',
            'type' => File::class,
            'attributes' => [
                'id' => 'FILE',
                'class' => 'form-input',
                'onchange' => 'form.submit()',
            ],
            'options' => [
                'label' => 'Upload File',
            ],
        ]);
        
        $this->add([
            'name' => 'REFERENCE',
            'type' => Hidden::class,
        ]);
        
        $this->add(new Csrf('SECURITY'));
        
        $this->add([
            'name' => 'SUBMIT',
            'type' => Submit::class,
            'attributes' => [
                'value' => 'Submit',
                'class' => 'btn btn-primary',
                'id' => 'SUBMIT',
            ],
        ]);
    }
    
    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        
        $inputFilter->add([
            'name' => 'FILE',
            'required' => FALSE,
        ]);
        
        $this->setInputFilter($inputFilter);
    }
}