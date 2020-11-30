<?php
namespace Files\Controller\Factory;

use Files\Controller\FilesController;
use Files\Form\FilesUploadForm;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class FilesControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new FilesController();
        $controller->form = new FilesUploadForm();
        return $controller;
    }
}