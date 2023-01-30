<?php
namespace Files\Controller\Factory;

use Files\Controller\FilesConfigController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class FilesConfigControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new FilesConfigController();
        $controller->setDbAdapter($container->get('files-model-adapter'));
        return $controller;
    }
}