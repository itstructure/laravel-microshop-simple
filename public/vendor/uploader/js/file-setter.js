$(document).ready(function() {

    /**
     * Handler to catch press on insert button.
     */
    function frameInsertHandler() {

        let modal = $(this).parents('.modal');
        let fileForm = $(this).contents().find("#file_form");

        fileForm.on('click', '[role="insert-file"]', function(e) {
            e.preventDefault();

            let formData = new FormData(fileForm[0]);

            let mainInput = null;
            let dataInputId = modal.attr('data-input-id');
            if (dataInputId) {
                mainInput = $('#' + dataInputId);
                //mainInput.trigger("fileInsert", [insertedDataType]);
            }

            let mediaFileContainerId = modal.attr('data-mediafile-container-id');
            if (mediaFileContainerId) {
                let url = modal.attr('data-file-preview-route');
                $.ajax({
                    type: 'POST',
                    url: url,
                    cache: false,
                    dataType: 'html',
                    data: {
                        id: formData.get('id'),
                        location: 'existing',
                        _token: formData.get('_token')
                    },
                    success: function (data) {
                        $('#' + mediaFileContainerId).html(data);
                    },
                    error: function (xhr, status, err) {
                        console.error(url, status, err.toString());
                    }
                });
            }

            let titleContainerId = modal.attr('data-title-container-id');
            if (titleContainerId) {
                $('#' + titleContainerId).html(formData.get('data[title]'));
            }

            let descriptionContainerId = modal.attr('data-description-container-id');
            if (descriptionContainerId) {
                $('#' + descriptionContainerId).html(formData.get('data[description]'));
            }

            let insertedDataType = modal.attr('data-inserted-data-type');
            if (insertedDataType && mainInput) {
                mainInput.val(formData.get(insertedDataType));
            }

            modal.modal('hide');
        });
    }

    /**
     * Load file manager.
     */
    $('[role="load-file-manager"]').on("click", function(e) {
        e.preventDefault();

        let modal = $('[data-open-btn-id="' + $(this).attr('id') + '"].modal'),
            fileManagerRoute = modal.attr('data-file-manager-route'),
            ownerName = modal.attr('data-owner-name'),
            ownerId = modal.attr('data-owner-id'),
            ownerAttribute = modal.attr('data-owner-attribute');

        let paramsArray = [];
        let paramsQuery = '';

        if (ownerName) {
            paramsArray.owner_name = ownerName;
        }

        if (ownerId) {
            paramsArray.owner_id = ownerId;
        }

        if (ownerAttribute) {
            paramsArray.owner_attribute = ownerAttribute;
        }

        for (let key in paramsArray) {
            let paramString = key + '=' + paramsArray[key];
            paramsQuery += paramsQuery == '' ? paramString : '&' + paramString;
        }

        if (paramsQuery != '') {
            fileManagerRoute += '?' + paramsQuery;
        }

        let iframe = $('<iframe src="' + fileManagerRoute + '" frameborder="0" class="file-manager-frame"></iframe>');

        iframe.on('load', frameInsertHandler);
        modal.find('.modal-body').html(iframe);
        modal.modal('show');
    });

    /**
     * Clear value in main input.
     */
    $('[role="clear-file"]').on("click", function(e) {
        e.preventDefault();

        let clearInputId = $(this).attr('data-input-id');
        if (clearInputId) {
            $('#' + clearInputId).val('');
        }

        let mediafileContainerId = $(this).attr('data-mediafile-container-id');
        if (mediafileContainerId) {
            $('#' + mediafileContainerId).empty();
        }

        let titleContainerId = $(this).attr('data-title-container-id');
        if (titleContainerId) {
            $('#' + titleContainerId).empty();
        }

        let descriptionContainerId = $(this).attr('data-description-container-id');
        if (descriptionContainerId) {
            $('#' + descriptionContainerId).empty();
        }
    });
});
