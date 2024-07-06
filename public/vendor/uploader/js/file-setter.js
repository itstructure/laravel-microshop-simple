$(document).ready(function() {

    function insertBase(modal, fileId, title, description) {
        let mainInput = null;
        let dataInputId = modal.attr('data-input-id');
        if (dataInputId) {
            mainInput = $('#' + dataInputId);
            mainInput.trigger("beforeInsert", fileId);
        }

        let mediaFileContainerId = modal.attr('data-mediafile-container-id');
        if (mediaFileContainerId) {
            let url = window.route_file_preview;
            $.ajax({
                type: 'POST',
                url: url,
                cache: false,
                dataType: 'html',
                data: {
                    id: fileId,
                    location: 'existing',
                    _token: window.csrf_token
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
            $('#' + titleContainerId).html(title);
        }

        let descriptionContainerId = modal.attr('data-description-container-id');
        if (descriptionContainerId) {
            $('#' + descriptionContainerId).html(description);
        }

        if (mainInput) {
            mainInput.val(fileId);
        }

        modal.modal('hide');
    }

    function frameInsertHandlerFromEditor() {

        let modal = $(this).parents('.modal');
        let editForm = $(this).contents().find("#edit_form");

        editForm.on('click', '[role="insert-file"]', function(e) {
            e.preventDefault();

            let formData = new FormData(editForm[0]);

            insertBase(
                modal,
                formData.get('id'),
                formData.get('data[title]'),
                formData.get('data[description]')
            );
        });
    }

    function frameInsertHandlerFromList() {

        let modal = $(this).parents('.modal');
        let fileListContainer = $(this).contents().find("#file_list");

        fileListContainer.on('click', '[role="insert-file"]', function(e) {
            e.preventDefault();

            let listItemDataEl = $(e.target).parents('[role="list-item-data"]');

            insertBase(
                modal,
                listItemDataEl.attr('data-file-id'),
                listItemDataEl.attr('data-file-title'),
                listItemDataEl.attr('data-file-description')
            );
        });
    }

    $('[role="load-file-manager"]').on("click", function(e) {
        e.preventDefault();

        let modal = $('[data-open-btn-id="' + $(this).attr('id') + '"].modal'),
            fileManagerRoute = window.route_file_list_manager,
            ownerName = modal.attr('data-owner-name'),
            ownerId = modal.attr('data-owner-id'),
            ownerAttribute = modal.attr('data-owner-attribute');

        let paramsData = {from_file_setter: 1};
        let paramsQuery = '';

        if (ownerName) {
            paramsData.owner_name = ownerName;
        }

        if (ownerId) {
            paramsData.owner_id = ownerId;
        }

        if (ownerAttribute) {
            paramsData.owner_attribute = ownerAttribute;
        }

        for (let key in paramsData) {
            let paramString = key + '=' + paramsData[key];
            paramsQuery += paramsQuery === '' ? paramString : '&' + paramString;
        }

        if (paramsQuery !== '') {
            fileManagerRoute += '?' + paramsQuery;
        }

        let iframe = $('<iframe src="' + fileManagerRoute + '" frameborder="0" class="file-manager-frame"></iframe>');

        iframe.on('load', frameInsertHandlerFromList);
        iframe.on('load', frameInsertHandlerFromEditor);
        modal.find('.modal-body').html(iframe);
        modal.modal('show');
    });

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
