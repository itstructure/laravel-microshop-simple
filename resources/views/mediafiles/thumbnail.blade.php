<div id="thumbnail_container">

</div>
@php
    $fileSetterConfig = [
        'model' => $model,
        'attribute' => Itstructure\MFU\Processors\SaveProcessor::FILE_TYPE_THUMB,
        'neededFileType' => Itstructure\MFU\Processors\SaveProcessor::FILE_TYPE_THUMB,
        'openButtonName' => 'Set thumbnail',
        'clearButtonName' => 'Clear',
        'mediafileContainerId' => 'thumbnail_container',
        'subDir' => $model->getTable()
    ];

    $ownerConfig = isset($ownerParams) && is_array($ownerParams) ? array_merge([
        'ownerAttribute' => Itstructure\MFU\Processors\SaveProcessor::FILE_TYPE_THUMB
    ], $ownerParams) : [];

    $fileSetterConfig = array_merge($fileSetterConfig, $ownerConfig);
@endphp
@fileSetter($fileSetterConfig)