<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data Types Schemas
    |--------------------------------------------------------------------------
    */
    "schemas" => [
        \App\Schema\Tables\UserDataType::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Class is DataType
    |
    */
    "menu" => [
        ["class" => \App\Schema\Tables\UserDataType::class],
        [
            "custom"     => true,
            'locale'     => 'voyager::seeders.menu_items.dashboard',
            'route'      => 'voyager.dashboard',
            'icon_class' => 'voyager-boat',
        ],
        [
            "custom"     => true,
            'locale'     => 'voyager::seeders.menu_items.media',
            'route'      => 'voyager.media.index',
            'icon_class' => 'voyager-images',
        ],
        [
            "custom"     => true,
            'locale'     => 'voyager::seeders.menu_items.roles',
            'route'      => 'voyager.roles.index',
            'icon_class' => 'voyager-lock',
        ],
        [
            "custom"     => true,
            'locale'     => 'voyager::seeders.menu_items.tools',
            'icon_class' => 'voyager-tools',
            "children"   => [
                [
                    "custom"     => true,
                    'locale'     => 'voyager::seeders.menu_items.menu_builder',
                    'route'      => 'voyager.menus.index',
                    'icon_class' => 'voyager-list',
                ],
                [
                    "custom"     => true,
                    'locale'     => 'voyager::seeders.menu_items.database',
                    'route'      => 'voyager.database.index',
                    'icon_class' => 'voyager-data',
                ],
                [
                    "custom"     => true,
                    'locale'     => 'voyager::seeders.menu_items.compass',
                    'route'      => 'voyager.compass.index',
                    'icon_class' => 'voyager-compass',
                ],
                [
                    "custom"     => true,
                    'locale'     => 'voyager::seeders.menu_items.bread',
                    'route'      => 'voyager.bread.index',
                    'icon_class' => 'voyager-bread',
                ],
                [
                    "custom"     => true,
                    'locale'     => 'voyager::seeders.menu_items.settings',
                    'route'      => 'voyager.settings.index',
                    'icon_class' => 'voyager-settings',
                ],
            ]
        ],
    ]
];
