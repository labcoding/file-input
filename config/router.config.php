<?php

return [
    'router' => [
        'routes' => [
            'file-input' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/file-input',
                    'defaults' => [
                        'controller' => 'sebaks-zend-mvc-controller',
                        'allowedMethods' => ['GET', 'POST'],
                        'service' => 'UserAvatar\FileInputService',
                    ],
                ],
            ],
        ],
    ],
];
