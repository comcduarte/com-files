<?php
namespace Files;

use Files\View\Helper\Files;
use Files\View\Helper\Factory\FilesFactory;

return [
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