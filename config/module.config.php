<?php

namespace LabCoding\FileInput;

return [
    'file_input' => [
        'UserAvatar' => [
            'property' => 'avatar',
            'uploader' => 'FileInput\File\Uploader',
            'path' => [
                'root' => './public',
                'pathPrefix' => '/data/user/avatar',
            ],
            'name' => 'FileInput\File\Name',
            'preview' => '/img/profile_user.png'
        ],
    ],

    'service_manager' => [
        'abstract_factories' => [
            Service\FileInputServiceAbstractFactory::class,
        ],
        'invokables' => [
            'FileInput\File\Name' => File\FileName::class,
            'FileInput\File\Image\Name' => File\Image\ImageFileName::class,
            'FileInput\File\Uploader' => Storage\Uploader::class,
        ],
        'factories' => [
            'FileInput\File\Path' => File\PathFactory::class,
        ]
    ],

    'view_helpers' => [
        'factories' => [
            'fileInput' => View\Helper\FileInputFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
