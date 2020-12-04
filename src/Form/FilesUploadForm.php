<?php
namespace Files\Form;

use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Submit;
use Laminas\InputFilter\InputFilter;
use Laminas\Form\Element\Hidden;

class FilesUploadForm extends Form
{
    public function init()
    {
        $this->add([
            'name' => 'FILE',
            'type' => File::class,
            'attributes' => [
                'id' => 'FILE',
                'class' => 'custom-file-input',
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
            'filters' => [
            ],
            'validators' => [
                [
                    'name'    => 'FileMimeType',
                    'options' => [
                        'mimeType'  => ['application/pdf', 'text/plain']
                    ]
                ],
            ],
        ]);
        
        $this->setInputFilter($inputFilter);
    }
}