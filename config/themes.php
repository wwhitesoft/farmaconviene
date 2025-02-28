<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shop Theme Configuration
    |--------------------------------------------------------------------------
    |
    | All the configurations are related to the shop themes.
    |
    */

    'shop-default' => 'farmaconviene',

    'shop' => [
        'default' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/shop/default',
            'views_path'  => 'resources/themes/default/views',

            'vite'        => [
                'hot_file'                 => 'shop-default-vite.hot',
                'build_directory'          => 'themes/shop/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
        'custom-theme' => [
            'name'        => 'Custom Theme',
            'assets_path' => 'public/themes/custom-theme/assets',
            'views_path'  => 'resources/themes/custom-theme/views',

            'vite' => [
                'hot_file'                 => 'custom-theme-vite.hot',
                'build_directory'          => 'themes/custom-theme/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
        'farmcaconviene' => [
            'name'        => 'Farmaconviene',
            'assets_path' => 'public/themes/farmaconviene/assets',
            'views_path'  => 'resources/themes/farmaconviene/views',

            'vite' => [
                'hot_file'                 => 'farmaconviene-vite.hot',
                'build_directory'          => 'themes/farmaconviene/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Theme Configuration
    |--------------------------------------------------------------------------
    |
    | All the configurations are related to the admin themes.
    |
    */

    'admin-default' => 'default',

    'admin' => [
        'default' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/admin/default',
            'views_path'  => 'resources/admin-themes/default/views',

            'vite'        => [
                'hot_file'                 => 'admin-default-vite.hot',
                'build_directory'          => 'themes/admin/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],
];
