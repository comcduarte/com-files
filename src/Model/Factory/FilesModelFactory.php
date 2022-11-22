<?php
namespace Files\Model\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Files\Model\FilesModel;

class FilesModelFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $model = new FilesModel();
        $model->setDbAdapter($container->get('files-model-adapter'));
        return $model;
    }
}