<?php
namespace Files\Controller\Factory;

use Files\Controller\FilesController;
use Files\Form\FilesUploadForm;
use Files\Model\FilesModel;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class FilesControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new FilesController();
        $controller->form = new FilesUploadForm();
        $controller->setDbAdapter($container->get('files-model-adapter'));
        $controller->setFiles($container->get(FilesModel::class));
        return $controller;
    }
}