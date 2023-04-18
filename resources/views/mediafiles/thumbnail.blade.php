<div id="thumbnail-container">

</div>
@php
    $fileSetterConfig = [
        'model' => $model,
        'attribute' => \App\Services\Uploader\src\Processors\SaveProcessor::FILE_TYPE_THUMB,
        'neededFileType' => \App\Services\Uploader\src\Processors\SaveProcessor::FILE_TYPE_THUMB,
        'openButtonName' => 'Set thumbnail',
        'clearButtonName' => 'Clear',
        'mediafileContainerId' => '#thumbnail-container',
        'subDir' => $model->getTable()
    ];

    $ownerConfig = isset($ownerParams) && is_array($ownerParams) ? array_merge([
        'ownerAttribute' => \App\Services\Uploader\src\Processors\SaveProcessor::FILE_TYPE_THUMB
    ], $ownerParams) : [];

    $fileSetterConfig = array_merge($fileSetterConfig, $ownerConfig);
@endphp
@fileSetter($fileSetterConfig)