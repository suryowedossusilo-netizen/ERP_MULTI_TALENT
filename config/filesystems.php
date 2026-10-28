<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],
        'public' => [
            'driver' => 'local',
            'root' => env('PUBLIC_STORAGE_ROOT')
                ? (realpath(base_path(env('PUBLIC_STORAGE_ROOT'))) ?: base_path(env('PUBLIC_STORAGE_ROOT')))
                : storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
    ],
    'links' => [public_path('storage') => storage_path('app/public')],
];
