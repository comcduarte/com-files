<?php
namespace Files\Form;

use Laminas\Form\Form;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Submit;
use Laminas\InputFilter\InputFilter;

class FilesUploadForm extends Form
{
    public $path;
    
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
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target'    => $this->path,
                        'useUploadName' => TRUE,
                        'useUploadExtension' => TRUE,
                        'overwrite' => TRUE,
                        'randomize' => FALSE,
                    ],
                ],
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