<?php

use App\Services\Uploader\UploadService;

return [
    'baseUrl' => config('app.url'),
    'renameFiles' => true,
    'checkExtensionByMimeType' => true,
    'fileMaxSize' => 1024 * 1024 * 64,
    'fileExtensions' => [
        UploadService::FILE_TYPE_THUMB => [
            'png', 'jpg', 'jpeg', 'gif',
        ],
        UploadService::FILE_TYPE_IMAGE => [
            'png', 'jpg', 'jpeg', 'gif',
        ],
        UploadService::FILE_TYPE_AUDIO => [
            'mp3',
        ],
        UploadService::FILE_TYPE_VIDEO => [
            'mp4', 'ogg', 'ogv', 'oga', 'ogx', 'webm',
        ],
        UploadService::FILE_TYPE_APP => [
            'doc', 'docx', 'rtf', 'pdf', 'rar', 'zip', 'jar', 'mcd', 'xls',
        ],
        UploadService::FILE_TYPE_TEXT => [
            'txt',
        ],
        UploadService::FILE_TYPE_OTHER => null,
    ],
    'thumbSizes' => [
        UploadService::THUMB_ALIAS_DEFAULT => [
            'name' => 'Default size',
            'size' => [200, null],
        ],
        UploadService::THUMB_ALIAS_SMALL => [
            'name' => 'Small size',
            'size' => [100, null],
        ],
        UploadService::THUMB_ALIAS_MEDIUM => [
            'name' => 'Medium size',
            'size' => [400, null],
        ],
        UploadService::THUMB_ALIAS_LARGE => [
            'name' => 'Large size',
            'size' => [970, null],
        ],
    ],
    'thumbFilenameTemplate' => '{original}-{width}-{height}-{alias}.{extension}',
    'uploadDirectories' => [
        UploadService::FILE_TYPE_IMAGE => 'images',
        UploadService::FILE_TYPE_AUDIO => 'audio',
        UploadService::FILE_TYPE_VIDEO => 'video',
        UploadService::FILE_TYPE_APP => 'application',
        UploadService::FILE_TYPE_TEXT => 'text',
        UploadService::FILE_TYPE_OTHER => 'other',
    ],
];
