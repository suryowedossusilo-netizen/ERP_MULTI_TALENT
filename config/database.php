<?php
return [
    'default' => env('DB_CONNECTION', 'sqlite'),
    'connections' => [
        'sqlite' => ['driver' => 'sqlite', 'database' => env('DB_DATABASE', database_path('database.sqlite')), 'foreign_key_constraints' => true],
        'mysql' => ['driver' => 'mysql', 'host' => env('DB_HOST', '127.0.0.1'), 'database' => env('DB_DATABASE'), 'username' => env('DB_USERNAME'), 'password' => env('DB_PASSWORD')],
    ],
];
