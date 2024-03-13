<div id="{{ !empty($model->id) ? 'thumbnail_container_' . $model->id : 'thumbnail_container' }}"></div>
<div id="{{ !empty($model->id) ? 'thumbnail_title_' . $model->id : 'thumbnail_title' }}"></div>
<div id="{{ !empty($model->id) ? 'thumbnail_description_' . $model->id : 'thumbnail_description' }}"></div>
@php
    $fileSetterConfig = [
        'model' => $model,
        'attribute' => Itstructure\MFU\Processors\SaveProcessor::FILE_TYPE_THUMB,
        'openButtonName' => 'Set thumbnail',
        'clearButtonName' => 'Clear',
        'mediafileContainerId' => !empty($model->id) ? 'thumbnail_container_' . $model->id : 'thumbnail_container',
        'titleContainerId' => !empty($model->id) ? 'thumbnail_title_' . $model->id : 'thumbnail_title',
        'descriptionContainerId' => !empty($model->id) ? 'thumbnail_description_' . $model->id : 'thumbnail_description',
        'callbackBeforeInsert' => 'function (data) {alert(data);}',
        'insertedDataType' => Itstructure\MFU\Views\FileSetter::INSERTED_DATA_ID,
        //'neededFileType' => Itstructure\MFU\Processors\SaveProcessor::FILE_TYPE_THUMB,
        //'subDir' => $model->getTable()
    ];

    $ownerConfig = isset($ownerParams) && is_array($ownerParams) ? array_merge([
        'ownerAttribute' => Itstructure\MFU\Processors\SaveProcessor::FILE_TYPE_THUMB
    ], $ownerParams) : [];

    $fileSetterConfig = array_merge($fileSetterConfig, $ownerConfig);
@endphp
@fileSetter($fileSetterConfig)
