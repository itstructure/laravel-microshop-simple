<div class="input-group">
    @include('input', [
        'attribute' => $attribute,
        'value' => $value,
        'inputId' => $inputId
    ])
    <span class="input-group-btn">
        @include('open_button', [
            'openButtonId' => $openButtonId,
            'openButtonName' => $openButtonName
        ])
        @include('clear_button', [
            'inputId' => $inputId,
            'mediafileContainerId' => $mediafileContainerId,
            'clearButtonName' => $clearButtonName
        ])
    </span>
    <span class="delete-box">

    </span>
</div>
@include('layouts.modal', [
    'inputId' => $inputId,
    'openButtonId' => $openButtonId
])
