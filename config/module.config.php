<?php
namespace Files;

use Files\Controller\FilesConfigController;
use Files\Controller\FilesController;
use Files\Controller\Factory\FilesConfigControllerFactory;
use Files\Controller\Factory\FilesControllerFactory;
use Files\Model\FilesModel;
use Files\Model\Factory\FilesModelFactory;
use Files\Service\Factory\FilesModelAdapterFactory;
use Files\View\Helper\BoxFiles;
use Files\View\Helper\Files;
use Files\View\Helper\Factory\BoxFilesFactory;
use Files\View\Helper\Factory\FilesFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'files' => [
                'type' => Literal::class,
                'priority' => 1,
                'options' => [
                    'route' => '/files',
                    'defaults' => [
                        'controller' => FilesController::class,
                    ],
                ],
                'may_terminate' => TRUE,
                'child_routes' => [
                    'config' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/config[/:action]',
                            'defaults' => [
                                'action' => 'index',
                                'controller' => FilesConfigController::class,
                            ],
                        ],
                    ],
                    'default' => [
                        'type' => Segment::class,
                        'priority' => -100,
                        'options' => [
                            'route' => '/[:action[/:uuid]]',
                            'defaults' => [
                                'action' => 'index',
                                'controller' => FilesController::class,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'acl' => [
        'EVERYONE' => [
            'files/default' => [],
        ],
        'admin' => [
            'files/config' => [],
        ],
    ],
    'controllers' => [
        'factories' => [
            FilesController::class => FilesControllerFactory::class,
            FilesConfigController::class => FilesConfigControllerFactory::class,
        ],
    ],
    'navigation' => [
        'default' => [
            'settings' => [
                'label' => 'Settings',
                'pages' => [
                    'files' => [
                        'label' => 'Files Settings',
                        'route' => 'files/config',
                        'action' => 'index',
                        'resource' => 'files/config',
                        'privilege' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'aliases' => [
        ],
        'factories' => [
            FilesModel::class => FilesModelFactory::class,
            'files-model-adapter' => FilesModelAdapterFactory::class,
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'boxFiles' => BoxFiles::class,
            'relatedFiles' => Files::class,
        ],
        'factories' => [
            BoxFiles::class => BoxFilesFactory::class,
            Files::class => FilesFactory::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'boxfiles' => __DIR__ . '/../view/files/files/boxfiles.phtml',
            'files' => __DIR__ . '/../view/files/files/files.phtml',
            'files_layout' => __DIR__ . '/../view/files/layouts/files_layout.phtml',
            'files_view' => __DIR__ . '/../view/files/files/view.phtml',
            'files_config' => __DIR__ . '/../view/files/files/config.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];