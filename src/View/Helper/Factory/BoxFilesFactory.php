<?php
namespace Files\View\Helper\Factory;

use Files\View\Helper\BoxFiles;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class BoxFilesFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new BoxFiles();
        $helper->setAclService($container->get('acl-service'));
        return $helper;
    }
}