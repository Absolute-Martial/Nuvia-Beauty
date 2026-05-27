<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    'imports_disk' => env('IMPORT_FILESYSTEM_DISK', env('FILESYSTEM_DISK', 'local')),

    'storage_contract' => env('STORAGE_DRIVER', 'local'),

    's3_compatible' => [
        'provider' => env('S3_PROVIDER', env('STORAGE_PROVIDER', 'generic')),
        'endpoint' => env('S3_ENDPOINT', env('AISTOR_ENDPOINT', env('AWS_ENDPOINT'))),
        'region' => env('S3_REGION', env('AISTOR_REGION', env('AWS_DEFAULT_REGION', 'us-east-1'))),
        'key' => env('S3_ACCESS_KEY_ID', env('AISTOR_ACCESS_KEY_ID', env('AWS_ACCESS_KEY_ID'))),
        'secret' => env('S3_SECRET_ACCESS_KEY', env('AISTOR_SECRET_ACCESS_KEY', env('AWS_SECRET_ACCESS_KEY'))),
        'use_path_style_endpoint' => env(
            'S3_USE_PATH_STYLE_ENDPOINT',
            env('AISTOR_USE_PATH_STYLE_ENDPOINT', env('AWS_USE_PATH_STYLE_ENDPOINT', false))
        ),
        'bucket_endpoint' => env('AISTOR_BUCKET_ENDPOINT', env('AWS_BUCKET_ENDPOINT', false)),
        'root' => env('AISTOR_ROOT_PREFIX', env('AWS_ROOT_PREFIX')),
        'upload_url_ttl_minutes' => (int) env('S3_UPLOAD_URL_TTL_MINUTES', 15),
        'download_url_ttl_minutes' => (int) env('S3_DOWNLOAD_URL_TTL_MINUTES', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('S3_ACCESS_KEY_ID', env('AISTOR_ACCESS_KEY_ID', env('AWS_ACCESS_KEY_ID'))),
            'secret' => env('S3_SECRET_ACCESS_KEY', env('AISTOR_SECRET_ACCESS_KEY', env('AWS_SECRET_ACCESS_KEY'))),
            'region' => env('S3_REGION', env('AISTOR_REGION', env('AWS_DEFAULT_REGION', 'us-east-1'))),
            'bucket' => env('S3_PUBLIC_BUCKET', env('AISTOR_BUCKET', env('AWS_BUCKET'))),
            'url' => env('AISTOR_PUBLIC_URL', env('AWS_URL')),
            'endpoint' => env('S3_ENDPOINT', env('AISTOR_ENDPOINT', env('AWS_ENDPOINT'))),
            'root' => env('AISTOR_ROOT_PREFIX', env('AWS_ROOT_PREFIX')),
            'bucket_endpoint' => env('AISTOR_BUCKET_ENDPOINT', env('AWS_BUCKET_ENDPOINT', false)),
            'use_path_style_endpoint' => env('S3_USE_PATH_STYLE_ENDPOINT', env('AISTOR_USE_PATH_STYLE_ENDPOINT', env('AWS_USE_PATH_STYLE_ENDPOINT', false))),
            'visibility' => 'public',
        ],

        's3_public' => [
            'driver' => 's3',
            'key' => env('S3_ACCESS_KEY_ID', env('AISTOR_ACCESS_KEY_ID', env('AWS_ACCESS_KEY_ID'))),
            'secret' => env('S3_SECRET_ACCESS_KEY', env('AISTOR_SECRET_ACCESS_KEY', env('AWS_SECRET_ACCESS_KEY'))),
            'region' => env('S3_REGION', env('AISTOR_REGION', env('AWS_DEFAULT_REGION', 'us-east-1'))),
            'bucket' => env('S3_PUBLIC_BUCKET', env('AISTOR_BUCKET', env('AWS_BUCKET'))),
            'url' => env('AISTOR_PUBLIC_URL', env('AWS_URL')),
            'endpoint' => env('S3_ENDPOINT', env('AISTOR_ENDPOINT', env('AWS_ENDPOINT'))),
            'root' => env('AISTOR_ROOT_PREFIX', env('AWS_ROOT_PREFIX')),
            'bucket_endpoint' => env('AISTOR_BUCKET_ENDPOINT', env('AWS_BUCKET_ENDPOINT', false)),
            'use_path_style_endpoint' => env('S3_USE_PATH_STYLE_ENDPOINT', env('AISTOR_USE_PATH_STYLE_ENDPOINT', env('AWS_USE_PATH_STYLE_ENDPOINT', false))),
            'visibility' => 'public',
        ],

        's3_beauty_inputs' => [
            'driver' => 's3',
            'key' => env('S3_ACCESS_KEY_ID', env('AISTOR_ACCESS_KEY_ID', env('AWS_ACCESS_KEY_ID'))),
            'secret' => env('S3_SECRET_ACCESS_KEY', env('AISTOR_SECRET_ACCESS_KEY', env('AWS_SECRET_ACCESS_KEY'))),
            'region' => env('S3_REGION', env('AISTOR_REGION', env('AWS_DEFAULT_REGION', 'us-east-1'))),
            'bucket' => env('S3_BEAUTY_INPUTS_BUCKET'),
            'endpoint' => env('S3_ENDPOINT', env('AISTOR_ENDPOINT', env('AWS_ENDPOINT'))),
            'root' => env('AISTOR_ROOT_PREFIX', env('AWS_ROOT_PREFIX')),
            'bucket_endpoint' => env('AISTOR_BUCKET_ENDPOINT', env('AWS_BUCKET_ENDPOINT', false)),
            'use_path_style_endpoint' => env('S3_USE_PATH_STYLE_ENDPOINT', env('AISTOR_USE_PATH_STYLE_ENDPOINT', env('AWS_USE_PATH_STYLE_ENDPOINT', false))),
            'visibility' => 'private',
        ],

        's3_beauty_results' => [
            'driver' => 's3',
            'key' => env('S3_ACCESS_KEY_ID', env('AISTOR_ACCESS_KEY_ID', env('AWS_ACCESS_KEY_ID'))),
            'secret' => env('S3_SECRET_ACCESS_KEY', env('AISTOR_SECRET_ACCESS_KEY', env('AWS_SECRET_ACCESS_KEY'))),
            'region' => env('S3_REGION', env('AISTOR_REGION', env('AWS_DEFAULT_REGION', 'us-east-1'))),
            'bucket' => env('S3_BEAUTY_RESULTS_BUCKET'),
            'endpoint' => env('S3_ENDPOINT', env('AISTOR_ENDPOINT', env('AWS_ENDPOINT'))),
            'root' => env('AISTOR_ROOT_PREFIX', env('AWS_ROOT_PREFIX')),
            'bucket_endpoint' => env('AISTOR_BUCKET_ENDPOINT', env('AWS_BUCKET_ENDPOINT', false)),
            'use_path_style_endpoint' => env('S3_USE_PATH_STYLE_ENDPOINT', env('AISTOR_USE_PATH_STYLE_ENDPOINT', env('AWS_USE_PATH_STYLE_ENDPOINT', false))),
            'visibility' => 'private',
        ],

        's3_beauty_calibration' => [
            'driver' => 's3',
            'key' => env('S3_ACCESS_KEY_ID', env('AISTOR_ACCESS_KEY_ID', env('AWS_ACCESS_KEY_ID'))),
            'secret' => env('S3_SECRET_ACCESS_KEY', env('AISTOR_SECRET_ACCESS_KEY', env('AWS_SECRET_ACCESS_KEY'))),
            'region' => env('S3_REGION', env('AISTOR_REGION', env('AWS_DEFAULT_REGION', 'us-east-1'))),
            'bucket' => env('S3_BEAUTY_CALIBRATION_BUCKET'),
            'endpoint' => env('S3_ENDPOINT', env('AISTOR_ENDPOINT', env('AWS_ENDPOINT'))),
            'root' => env('AISTOR_ROOT_PREFIX', env('AWS_ROOT_PREFIX')),
            'bucket_endpoint' => env('AISTOR_BUCKET_ENDPOINT', env('AWS_BUCKET_ENDPOINT', false)),
            'use_path_style_endpoint' => env('S3_USE_PATH_STYLE_ENDPOINT', env('AISTOR_USE_PATH_STYLE_ENDPOINT', env('AWS_USE_PATH_STYLE_ENDPOINT', false))),
            'visibility' => 'private',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
