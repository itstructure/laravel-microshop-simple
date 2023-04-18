<div role="filemanager-modal" class="modal" tabindex="-1"
     data-file-manager-route="{{ $fileManagerRoute }}"
     data-input-id="{{ $inputId }}"
     data-open-btn-id="{{ $openButtonId }}"
     data-mediafile-container-id="{{ isset($mediafileContainerId) ? $mediafileContainerId : '' }}"
     data-title-container-id="{{ isset($titleContainerId) ? $titleContainerId : '' }}"
     data-description-container-id="{{ isset($descriptionContainerId) ? $descriptionContainerId : '' }}"
     data-inserted-data-type="{{ isset($insertedDataType) ? $insertedDataType : '' }}"
     data-owner-name="{{ $ownerName }}"
     data-owner-id="{{ $ownerId }}"
     data-owner-attribute="{{ $ownerAttribute }}"
     data-needed-file-type="{{ $neededFileType }}"
     data-sub-dir="{{ $subDir }}"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body"></div>
        </div>
    </div>
</div>