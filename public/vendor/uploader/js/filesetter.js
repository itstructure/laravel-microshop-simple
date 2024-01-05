$(document).ready(function() {

    /**
     * Handler to catch press on insert button.
     */
    function frameInsertHandler() {

        var modal = $(this).parents('.modal');

        $(this).contents().find(".redactor").on('click', '[role="insert"]', function(e) {
            e.preventDefault();

            var fileInputs = $(this).parents('[role="file-inputs"]'),
                mediafileContainer = $(modal.attr("data-mediafile-container-id")),
                titleContainer = $(modal.attr("data-title-container-id")),
                descriptionContainer = $(modal.attr("data-description-container-id")),
                insertedDataType = modal.attr("data-inserted-data-type"),
                mainInput = $("#" + modal.attr("data-input-id"));

            mainInput.trigger("fileInsert", [insertedDataType]);

            if (mediafileContainer) {
                var fileType = fileInputs.attr("data-file-type"),
                    fileTypeShort = fileType.split('/')[0],
                    fileUrl = fileInputs.attr("data-file-url"),
                    baseUrl = fileInputs.attr("data-base-url"),
                    previewOptions = {
                        fileType: fileType,
                        fileUrl: fileUrl,
                        baseUrl: baseUrl
                    };

                if (fileTypeShort === 'image' || fileTypeShort === 'video' || fileTypeShort === 'audio') {
                    previewOptions.main = {width: fileInputs.attr("data-original-preview-width")};
                }

                var preview = getPreview(previewOptions);
                mediafileContainer.html(preview);

                /* Set title */
                if (titleContainer) {
                    var titleValue = $(fileInputs.contents().find('[role="file-title"]')).val();
                    titleContainer.html(titleValue);
                }

                /* Set description */
                if (descriptionContainer) {
                    var descriptionValue = $(fileInputs.contents().find('[role="file-description"]')).val();
                    descriptionContainer.html(descriptionValue);
                }
            }

            mainInput.val(fileInputs.attr("data-file-" + insertedDataType));
            modal.modal("hide");
        });
    }

    /**
     * Load file manager.
     */
    $('[role="filemanager-load"]').on("click", function(e) {
        e.preventDefault();

        var modal = $('[data-open-btn-id="'+$(this).attr('id')+'"].modal'),
            fileManagerRoute = modal.attr("data-file-manager-route"),
            ownerName = modal.attr("data-owner-name"),
            ownerId = modal.attr("data-owner-id"),
            ownerAttribute = modal.attr("data-owner-attribute");

        var paramsArray = [];
        var paramsQuery = '';

        if (ownerName) {
            paramsArray.owner_name = ownerName;
        }

        if (ownerId) {
            paramsArray.owner_id = ownerId;
        }

        if (ownerAttribute) {
            paramsArray.owner_attribute = ownerAttribute;
        }

        for (var index in paramsArray) {
            var paramString = index + '=' + paramsArray[index];
            paramsQuery += paramsQuery == '' ? paramString : '&' + paramString;
        }

        if (paramsQuery != '') {
            fileManagerRoute += '?' + paramsQuery;
        }

        var iframe = $('<iframe src="' + fileManagerRoute + '" frameborder="0" role="filemanager-frame"></iframe>');

        iframe.on("load", frameInsertHandler);
        modal.find(".modal-body").html(iframe);
        modal.modal("show");
    });

    /**
     * Clear value in main input.
     */
    $('[role="clear-input"]').on("click", function(e) {
        e.preventDefault();

        $("#" + $(this).attr("data-clear-element-id")).val("");
        $($(this).attr("data-mediafile-container")).empty();
    });
});
