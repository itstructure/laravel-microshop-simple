<?php

use App\Services\Uploader\UploaderService;
use App\Services\Uploader\Processors\BaseProcessor;

return [
    'storageType' => UploaderService::STORAGE_TYPE_LOCAL,
    'baseUrl' => config('app.url'),
    'baseConfig' => [
        'renameFiles' => true,
        'checkExtensionByMimeType' => true,
        'fileMaxSize' => 1024*1024*64,
        'fileExtensions' => [
            BaseProcessor::FILE_TYPE_THUMB => [
                'png', 'jpg', 'jpeg', 'gif',
            ],
            BaseProcessor::FILE_TYPE_IMAGE => [
                'png', 'jpg', 'jpeg', 'gif',
            ],
            BaseProcessor::FILE_TYPE_AUDIO => [
                'mp3',
            ],
            BaseProcessor::FILE_TYPE_VIDEO => [
                'mp4', 'ogg', 'ogv', 'oga', 'ogx', 'webm',
            ],
            BaseProcessor::FILE_TYPE_APP => [
                'doc', 'docx', 'rtf', 'pdf', 'rar', 'zip', 'jar', 'mcd', 'xls',
            ],
            BaseProcessor::FILE_TYPE_TEXT => [
                'txt',
            ],
            BaseProcessor::FILE_TYPE_OTHER => null,
        ],
        'thumbsConfig' => [
            BaseProcessor::THUMB_ALIAS_DEFAULT => [
                'name' => 'Default size',
                'size' => [200, null],
            ],
            BaseProcessor::THUMB_ALIAS_SMALL => [
                'name' => 'Small size',
                'size' => [100, null],
            ],
            BaseProcessor::THUMB_ALIAS_MEDIUM => [
                'name' => 'Medium size',
                'size' => [400, null],
            ],
            BaseProcessor::THUMB_ALIAS_LARGE => [
                'name' => 'Large size',
                'size' => [970, null],
            ],
        ],
        'thumbFilenameTemplate' => '{original}-{width}-{height}-{alias}.{extension}'
    ],
    'localConfig' => [
        'uploadRoot' => dirname($_SERVER['SCRIPT_FILENAME']),
        'uploadDirs' => [
            BaseProcessor::FILE_TYPE_IMAGE => storage_path('app/public') . DIRECTORY_SEPARATOR . 'images',
            BaseProcessor::FILE_TYPE_AUDIO => storage_path('app/public') . DIRECTORY_SEPARATOR . 'audio',
            BaseProcessor::FILE_TYPE_VIDEO => storage_path('app/public') . DIRECTORY_SEPARATOR . 'video',
            BaseProcessor::FILE_TYPE_APP => storage_path('app/public') . DIRECTORY_SEPARATOR . 'application',
            BaseProcessor::FILE_TYPE_TEXT => storage_path('app/public') . DIRECTORY_SEPARATOR . 'text',
            BaseProcessor::FILE_TYPE_OTHER => storage_path('app/public') . DIRECTORY_SEPARATOR . 'other',
        ]
    ],
    's3Config' => [
        'clientVersion' => 'latest',
        'credentials' => [
            'key' => env('AWS_S3_KEY'),
            'secret' => env('AWS_S3_SECRET'),
        ],
        'region' => 'us-west-2',
        'defaultBucket' => 'filesmodule2',
        'bucketsMap' => [],
        'uploadDirs' => [
            BaseProcessor::FILE_TYPE_IMAGE => 'images',
            BaseProcessor::FILE_TYPE_AUDIO => 'audio',
            BaseProcessor::FILE_TYPE_VIDEO => 'video',
            BaseProcessor::FILE_TYPE_APP => 'application',
            BaseProcessor::FILE_TYPE_TEXT => 'text',
            BaseProcessor::FILE_TYPE_OTHER => 'other',
        ],
    ],
];