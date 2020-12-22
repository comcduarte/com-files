<?php
namespace Files\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Files\View\Helper\Files;

class FilesFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new Files();
        $helper->setAclService($container->get('acl-service'));
        return $helper;
    }
}