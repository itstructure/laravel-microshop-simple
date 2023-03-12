<?php

use App\Services\Uploader\UploaderService;

return [
    'storageType' => UploaderService::STORAGE_TYPE_LOCAL,
    'baseUrl' => config('app.url'),
    'thumbsConfig' => [
        UploaderService::THUMB_ALIAS_DEFAULT => [
            'name' => 'Default size',
            'size' => [200, null],
        ],
        UploaderService::THUMB_ALIAS_SMALL => [
            'name' => 'Small size',
            'size' => [100, null],
        ],
        UploaderService::THUMB_ALIAS_MEDIUM => [
            'name' => 'Medium size',
            'size' => [400, null],
        ],
        UploaderService::THUMB_ALIAS_LARGE => [
            'name' => 'Large size',
            'size' => [970, null],
        ],
    ],
    'localConfig' => [
        'checkExtensionByMimeType' => false,
        'uploadRoot' => dirname($_SERVER['SCRIPT_FILENAME'])
    ],
    's3Config' => [
        'checkExtensionByMimeType' => false,
        'credentials' => [
            'key' => env('AWS_S3_KEY'),
            'secret' => env('AWS_S3_SECRET'),
        ],
        'region' => 'us-west-2',
        'defaultBucket' => 'filesmodule2',
    ],
];