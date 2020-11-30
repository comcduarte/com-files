<?php
namespace Files;

use Files\Controller\FilesController;
use Files\Controller\Factory\FilesControllerFactory;
use Files\View\Helper\Files;
use Files\View\Helper\Factory\FilesFactory;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'files' => [
                'type' => Segment::class,
                'priority' => 1,
                'options' => [
                    'route' => '/files[/:action]',
                    'defaults' => [
                        'controller' => FilesController::class,
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            FilesController::class => FilesControllerFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            Files::class => FilesFactory::class,
        ],
        'aliases' => [
            'relatedFiles' => Files::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];