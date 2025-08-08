<?php

return [

    'default' => env('DB_CONNECTION', 'landlord'),

    'connections' => [

        'landlord' => [
            'driver' => 'mysql',
            'host' => env('LANDLORD_DB_HOST', '127.0.0.1'),
            'port' => env('LANDLORD_DB_PORT', '3306'),
            'database' => env('LANDLORD_DB_DATABASE', 'landlord'),
            'username' => env('LANDLORD_DB_USERNAME', 'root'),
            'password' => env('LANDLORD_DB_PASSWORD', ''),
            'unix_socket' => env('LANDLORD_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

        'tenant' => [
            'driver' => 'mysql',
            'host' => env('TENANT_DB_HOST', '127.0.0.1'),
            'port' => env('TENANT_DB_PORT', '3306'),
            'database' => 'tenant-placeholder', // spatie switch task will override
            'username' => env('TENANT_DB_USERNAME', 'root'),
            'password' => env('TENANT_DB_PASSWORD', ''),
            'unix_socket' => env('TENANT_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],
    ],

    'migrations' => 'migrations',
];
