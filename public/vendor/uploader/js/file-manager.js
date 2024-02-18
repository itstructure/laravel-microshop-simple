function showPreLoader(preLoaderEl) {
    if (!preLoaderEl.hasClass('active')) {
        preLoaderEl.addClass('active');
    }
}

function hidePreLoader(preLoaderEl) {
    if (preLoaderEl.hasClass('active')) {
        preLoaderEl.removeClass('active');
    }
}

function submitFileForm(event) {
    event.preventDefault();

    let preLoaderEl = $('#edit_pre_loader');
    showPreLoader(preLoaderEl);

    let formData = new FormData(event.target);

    let modal = $(window.parent.document).find('.modal');
    let dataNeededFileType = modal.attr('data-needed-file-type');
    if (dataNeededFileType) {
        formData.append('data[needed_file_type]', dataNeededFileType);
    }
    let dataSubDir = modal.attr('data-sub-dir');
    if (dataSubDir) {
        formData.append('data[sub_dir]', dataSubDir);
    }

    requestAjax(window.route_file_update, formData,
        function (response) {
            hidePreLoader(preLoaderEl);
            let errors = response.errors && Object.keys(response.errors).length
                ? response.errors
                : {};
            catchFeedback('file_form', ['alt', 'title', 'description', 'file'], errors);
            if (response.success) {
                getPreview(formData.get('id'), 'fileinfo',
                    function(response) {
                        $('#file_preview').html(response);
                    },
                    function () {
                        showPreLoader($('#preview_pre_loader'));
                    },
                    function () {
                        hidePreLoader($('#preview_pre_loader'));
                    }
                );
            }

        }, function () {
            hidePreLoader(preLoaderEl);

        }, {
            dataType: 'json',
            processData: false,
            contentType: false
        });
}

function clearInput(fieldKey) {
    let fieldEl = $('#' + fieldKey);
    fieldEl.val('');
    if (fieldEl.hasClass('is-invalid')) {
        fieldEl.removeClass('is-invalid');
    }
    let feedbackEl = $('#validation_' + fieldKey + '_feedback');
    feedbackEl.html('');
}
