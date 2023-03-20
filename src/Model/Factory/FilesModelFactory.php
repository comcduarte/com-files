<?php
namespace Files\Model\Factory;

use Files\Model\FilesModel;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class FilesModelFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adapter = $container->get('files-model-adapter');
        $model = new FilesModel($adapter);
        return $model;
    }
}