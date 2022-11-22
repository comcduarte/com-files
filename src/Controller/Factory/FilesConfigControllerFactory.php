<?php
namespace Files\Controller\Factory;

use Files\Controller\FilesConfigController;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class FilesConfigControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new FilesConfigController();
        $controller->setDbAdapter($container->get('files-model-adapter'));
        return $controller;
    }
}