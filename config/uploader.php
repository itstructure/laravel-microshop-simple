<?php

use Illuminate\Validation\Rule;
use Itstructure\MFU\Processors\SaveProcessor;
use Itstructure\MFU\Services\Previewer;

return [
    'processor' => [
        'baseUrl' => config('app.url'),
        'renameFiles' => true,
        'checkExtensionByFileType' => false,
        'maxFileSize' => 100 * 1024,// Kilobytes
        'fileExtensions' => [
            SaveProcessor::FILE_TYPE_THUMB => [
                'png', 'jpg', 'jpeg', 'gif',
            ],
            SaveProcessor::FILE_TYPE_IMAGE => [
                'png', 'jpg', 'jpeg', 'gif',
            ],
            SaveProcessor::FILE_TYPE_AUDIO => [
                'mp3',
            ],
            SaveProcessor::FILE_TYPE_VIDEO => [
                'mp4', 'ogg', 'ogv', 'oga', 'ogx', 'webm',
            ],
            SaveProcessor::FILE_TYPE_APP => [
                'doc', 'docx', 'rtf', 'pdf', 'xls', 'xlsx', 'vsd', 'vsdx', 'ppt', 'pptx',
                'rar', 'zip', 'jar', 'mcd', 'exe',
            ],
            SaveProcessor::FILE_TYPE_APP_WORD => [
                'doc', 'docx', 'rtf',
            ],
            SaveProcessor::FILE_TYPE_APP_EXCEL => [
                'xls', 'xlsx',
            ],
            SaveProcessor::FILE_TYPE_APP_VISIO => [
                'vsd', 'vsdx',
            ],
            SaveProcessor::FILE_TYPE_APP_PPT => [
                'ppt', 'pptx',
            ],
            SaveProcessor::FILE_TYPE_APP_PDF => [
                'pdf',
            ],
            SaveProcessor::FILE_TYPE_TEXT => [
                'txt',
            ],
            SaveProcessor::FILE_TYPE_OTHER => null,
        ],
        'thumbSizes' => [
            SaveProcessor::THUMB_ALIAS_DEFAULT => [
                'name' => 'Default size',
                'size' => [200, null],
            ],
            SaveProcessor::THUMB_ALIAS_SMALL => [
                'name' => 'Small size',
                'size' => [100, null],
            ],
            SaveProcessor::THUMB_ALIAS_MEDIUM => [
                'name' => 'Medium size',
                'size' => [400, null],
            ],
            SaveProcessor::THUMB_ALIAS_LARGE => [
                'name' => 'Large size',
                'size' => [970, null],
            ],
        ],
        'thumbFilenameTemplate' => '{original}-{width}-{height}-{alias}.{extension}',
        'baseUploadDirectories' => [
            SaveProcessor::FILE_TYPE_IMAGE => 'images',
            SaveProcessor::FILE_TYPE_AUDIO => 'audio',
            SaveProcessor::FILE_TYPE_VIDEO => 'video',
            SaveProcessor::FILE_TYPE_APP => 'applications',
            SaveProcessor::FILE_TYPE_APP_WORD => 'word',
            SaveProcessor::FILE_TYPE_APP_EXCEL => 'excel',
            SaveProcessor::FILE_TYPE_APP_VISIO => 'visio',
            SaveProcessor::FILE_TYPE_APP_PPT => 'powerpoint',
            SaveProcessor::FILE_TYPE_APP_PDF => 'pdf',
            SaveProcessor::FILE_TYPE_TEXT => 'text',
            SaveProcessor::FILE_TYPE_OTHER => 'other',
        ],
        'metaDataValidationRules' => [
            'alt' => 'nullable|string|max:128',
            'title' => 'nullable|string|max:128',
            'description' => 'nullable|string|max:2048',
            'owner_id' => 'nullable|numeric',
            'owner_name' => 'nullable|string|max:64',
            'owner_attribute' => 'nullable|string|max:64',
            'needed_file_type' => [
                'nullable',
                Rule::in([
                    SaveProcessor::FILE_TYPE_THUMB,
                    SaveProcessor::FILE_TYPE_IMAGE,
                    SaveProcessor::FILE_TYPE_AUDIO,
                    SaveProcessor::FILE_TYPE_VIDEO,
                    SaveProcessor::FILE_TYPE_APP,
                    SaveProcessor::FILE_TYPE_APP_WORD,
                    SaveProcessor::FILE_TYPE_APP_EXCEL,
                    SaveProcessor::FILE_TYPE_APP_VISIO,
                    SaveProcessor::FILE_TYPE_APP_PPT,
                    SaveProcessor::FILE_TYPE_APP_PDF,
                    SaveProcessor::FILE_TYPE_TEXT,
                    SaveProcessor::FILE_TYPE_OTHER
                ])
            ],
            'sub_dir' => 'nullable|string|max:64'
        ],
        'metaDataValidationMessageTranslations' => [//It is very important to set without __() or trans().
            //It will be configured in a SaveProcessor by prepareValidationTranslations() method later.
            'required' => 'uploader::validation.required',
            'string' => 'uploader::validation.string',
            'numeric' => 'uploader::validation.numeric',
            'min' => 'uploader::validation.min',
            'max' => 'uploader::validation.max',
        ],
        'metaDataValidationAttributeTranslations' => [//It is very important to set without __() or trans().
            //It will be configured in a SaveProcessor by prepareValidationTranslations() method later.
            'alt' => 'uploader::main.alt',
            'title' => 'uploader::main.title',
            'description' => 'uploader::main.description',
        ],
        'fileValidationMessageTranslations' => [//It is very important to set without __() or trans().
            //It will be configured in a SaveProcessor by prepareValidationTranslations() method later.
            'required' => 'uploader::validation.required',
            'max' => 'uploader::validation.max_file_size',
            'mimes' => 'uploader::validation.mimes',
        ],
        'fileValidationAttributeTranslations' => [//It is very important to set without __() or trans().
            //It will be configured in a SaveProcessor by prepareValidationTranslations() method later.
            'file' => 'uploader::main.file',
        ],
        'visibility' => SaveProcessor::VISIBILITY_PUBLIC
    ],
    'routing' => [
        'middlewares' => ['auth'],
    ],
    'preview' => [
        'htmlAttributes' => [
            // For media display
            SaveProcessor::FILE_TYPE_IMAGE => [
                Previewer::LOCATION_FILE_ITEM => [
                    'width' => 100,
                    'height' => 100,
                    'class' => 'list-item'
                ],
                Previewer::LOCATION_FILE_INFO => [
                    //'width' => 400,//Optional
                    //'height' => 400,//Optional
                    //'class' => 'some-css-class',//Optional
                ],
                Previewer::LOCATION_EXISTING => [
                    //'width' => 400,//Optional
                    //'height' => 400,//Optional
                    //'class' => 'some-css-class',//Optional
                ],
            ],
            SaveProcessor::FILE_TYPE_AUDIO => [
                Previewer::LOCATION_FILE_ITEM => [
                    'width' => 300
                ],
                Previewer::LOCATION_FILE_INFO => [
                    'width' => 360
                ],
                Previewer::LOCATION_EXISTING => [
                    'width' => 360
                ],
            ],
            SaveProcessor::FILE_TYPE_VIDEO => [
                Previewer::LOCATION_FILE_ITEM => [
                    'width' => 300,
                    'height' => 240
                ],
                Previewer::LOCATION_FILE_INFO => [
                    'width' => 400,
                    'height' => 320
                ],
                Previewer::LOCATION_EXISTING => [
                    'width' => 400,
                    'height' => 320
                ],
            ],

            // For stubs
            SaveProcessor::FILE_TYPE_APP => [
                Previewer::LOCATION_FILE_ITEM => [
                    'width' => 100 . 'px',
                    'height' => 100 . 'px'
                ],
                Previewer::LOCATION_FILE_INFO => [
                    'width' => 300 . 'px',
                    'height' => 300 . 'px'
                ],
                Previewer::LOCATION_EXISTING => [
                    'width' => 200 . 'px',
                    'height' => 200 . 'px'
                ],
            ],
            SaveProcessor::FILE_TYPE_APP_WORD => [
                Previewer::LOCATION_FILE_ITEM => [
                    'width' => 100 . 'px',
                    'height' => 100 . 'px'
                ],
                Previewer::LOCATION_FILE_INFO => [
                    'width' => 300 . 'px',
                    'height' => 300 . 'px'
                ],
                Previewer::LOCATION_EXISTING => [
                    'width' => 200 . 'px',
                    'height' => 200 . 'px'
                ],
            ],
            SaveProcessor::FILE_TYPE_APP_EXCEL => [
                Previewer::LOCATION_FILE_ITEM => [
                    'width' => 100 . 'px',
                    'height' => 100 . 'px'
                ],
                Previewer::LOCATION_FILE_INFO => [
                    'width' => 300 . 'px',
                    'height' => 300 . 'px'
                ],
                Previewer::LOCATION_EXISTING => [
                    'width' => 200 . 'px',
                    'height' => 200 . 'px'
                ],
            ],
            SaveProcessor::FILE_TYPE_APP_VISIO => [
                Previewer::LOCATION_FILE_ITEM => [
                    'width' => 100 . 'px',
                    'height' => 100 . 'px'
                ],
                Previewer::LOCATION_FILE_INFO => [
                    'width' => 300 . 'px',
                    'height' => 300 . 'px'
                ],
                Previewer::LOCATION_EXISTING => [
                    'width' => 200 . 'px',
                    'height' => 200 . 'px'
                ],
            ],
            SaveProcessor::FILE_TYPE_APP_PPT => [
                Previewer::LOCATION_FILE_ITEM => [
                    'width' => 100 . 'px',
                    'height' => 100 . 'px'
                ],
                Previewer::LOCATION_FILE_INFO => [
                    'width' => 300 . 'px',
                    'height' => 300 . 'px'
                ],
                Previewer::LOCATION_EXISTING => [
                    'width' => 200 . 'px',
                    'height' => 200 . 'px'
                ],
            ],
            SaveProcessor::FILE_TYPE_APP_PDF => [
                Previewer::LOCATION_FILE_ITEM => [
                    'width' => 100 . 'px',
                    'height' => 100 . 'px'
                ],
                Previewer::LOCATION_FILE_INFO => [
                    'width' => 300 . 'px',
                    'height' => 300 . 'px'
                ],
                Previewer::LOCATION_EXISTING => [
                    'width' => 200 . 'px',
                    'height' => 200 . 'px'
                ],
            ],
            SaveProcessor::FILE_TYPE_TEXT => [
                Previewer::LOCATION_FILE_ITEM => [
                    'width' => 100 . 'px',
                    'height' => 100 . 'px'
                ],
                Previewer::LOCATION_FILE_INFO => [
                    'width' => 300 . 'px',
                    'height' => 300 . 'px'
                ],
                Previewer::LOCATION_EXISTING => [
                    'width' => 200 . 'px',
                    'height' => 200 . 'px'
                ],
            ],
            SaveProcessor::FILE_TYPE_OTHER => [
                Previewer::LOCATION_FILE_ITEM => [
                    'width' => 100 . 'px',
                    'height' => 100 . 'px'
                ],
                Previewer::LOCATION_FILE_INFO => [
                    'width' => 300 . 'px',
                    'height' => 300 . 'px'
                ],
                Previewer::LOCATION_EXISTING => [
                    'width' => 200 . 'px',
                    'height' => 200 . 'px'
                ],
            ]
        ],
        'thumbAlias' => [
            Previewer::LOCATION_FILE_ITEM => SaveProcessor::THUMB_ALIAS_SMALL,
            Previewer::LOCATION_FILE_INFO => SaveProcessor::THUMB_ALIAS_MEDIUM,
            Previewer::LOCATION_EXISTING => SaveProcessor::THUMB_ALIAS_MEDIUM,
        ]
    ],
    'albums' => [
        'layout' => 'adminlte::page', // Example: 'layout' => 'adminlte::page'
    ]
];
