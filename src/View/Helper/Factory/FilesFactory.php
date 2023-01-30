<?php
namespace Files\View\Helper\Factory;

use Files\View\Helper\Files;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class FilesFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new Files();
        $helper->setAclService($container->get('acl-service'));
        return $helper;
    }
}