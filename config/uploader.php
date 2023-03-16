<?php

use App\Services\Uploader\Processors\BaseProcessor;

return [
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
        'thumbSizes' => [
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
        'thumbFilenameTemplate' => '{original}-{width}-{height}-{alias}.{extension}',
        'uploadDirectories' => [
            BaseProcessor::FILE_TYPE_IMAGE => 'images',
            BaseProcessor::FILE_TYPE_AUDIO => 'audio',
            BaseProcessor::FILE_TYPE_VIDEO => 'video',
            BaseProcessor::FILE_TYPE_APP => 'application',
            BaseProcessor::FILE_TYPE_TEXT => 'text',
            BaseProcessor::FILE_TYPE_OTHER => 'other',
        ],
    ],
    'localConfig' => [],
    's3Config' => [
        'bucketsMap' => [],
    ],
];