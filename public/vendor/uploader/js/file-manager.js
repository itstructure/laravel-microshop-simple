function requestAjax(url, sendData, successCallback, errorCallback, options) {
    let params = {
        type: 'POST',
        url: url,
        cache: false,
        data: sendData !== undefined ? sendData : {},
        success: function (data) {
            if (successCallback) {
                successCallback(data);
            }
        },
        error: function (xhr, status, err) {
            console.error(url, status, err.toString());
            if (errorCallback) {
                errorCallback(xhr, status, err);
            }
        }
    };
    if (options !== undefined) {
        for (let key in options) {
            params[key] = options[key];
        }
    }
    $.ajax(params);
}

function catchFeedback(formEl, fields, errors) {
    for (let i in fields) {
        let fieldKey = fields[i];
        let fieldEl = formEl.find('[role="' + fieldKey + '"]');
        let feedbackEl = formEl.find('[role="validation_' + fieldKey + '_feedback"]');
        if (errors[fieldKey] !== undefined) {
            if (!fieldEl.hasClass('is-invalid')) {
                fieldEl.addClass('is-invalid');
            }
            feedbackEl.html(errors[fieldKey]);
        } else {
            if (fieldEl.hasClass('is-invalid')) {
                fieldEl.removeClass('is-invalid');
            }
            feedbackEl.html('');
        }
    }
}

function getPreview(id, location, successCallback, showPreLoaderCallback, hidePreLoaderCallback) {
    if (showPreLoaderCallback) {
        showPreLoaderCallback();
    }
    requestAjax(window.route_file_preview, {
        id: id,
        location: location,
        _token: window.csrf_token

    }, function (response) {
        successCallback(response);
        if (hidePreLoaderCallback) {
            hidePreLoaderCallback();
        }

    }, function () {
        if (hidePreLoaderCallback) {
            hidePreLoaderCallback();
        }

    }, {
        dataType: 'html'
    });
}

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

function submitEditForm(event) {
    event.preventDefault();

    let formEl = $(event.target);
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
            catchFeedback(formEl, ['alt', 'title', 'description', 'file'], errors);
            if (response.success) {
                getPreview(formData.get('id'), 'fileinfo',
                    function (response) {
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

function submitUploadForm(event) {
    event.preventDefault();

    let formEl = $(event.target);
    let uploadBlockEl = formEl.parents('[role="upload-block"]');
    let preLoaderEl = uploadBlockEl.find('[role="upload_pre_loader"]');

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

    requestAjax(window.route_file_upload, formData,
        function (response) {
            hidePreLoader(preLoaderEl);
            let errors = response.errors && Object.keys(response.errors).length
                ? response.errors
                : {};
            catchFeedback(formEl, ['alt', 'title', 'description', 'file'], errors);
            if (response.success) {
                if (!formEl.hasClass('completed')) {
                    formEl.addClass('completed');
                }
                getPreview(response.id, 'fileinfo',
                    function (response) {
                        uploadBlockEl.find('[role="file_preview"]').html(response);
                    },
                    function () {
                        showPreLoader(uploadBlockEl.find('[role="preview_pre_loader"]'));
                    },
                    function () {
                        hidePreLoader(uploadBlockEl.find('[role="preview_pre_loader"]'));
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

function groupUpload() {
    let uploadForms = $('#file_upload').find('[role="upload-form"]:not(.completed)');
    uploadForms.each(function () {
        $(this).submit();
    });
}

function newUploadBlock() {
    let templateHtml = $('#upload_block').html();
    let workspace = $('#file_upload');
    workspace.append(templateHtml);
}

function cancelUploadBlock(event) {
    event.preventDefault();
    let buttonEl = $(event.target);
    let uploadBlockEl = buttonEl.parents('[role="upload-block"]');
    uploadBlockEl.fadeOut();
}

function cancelAllUploadBlocks() {
    let uploadBlocks = $('#file_upload').find('[role="upload-block"]');
    uploadBlocks.each(function () {
        $(this).fadeOut();
    });
}

function clearInput(fieldKey) {
    let fieldEl = $('[role="' + fieldKey + '"]');
    fieldEl.val('');
    if (fieldEl.hasClass('is-invalid')) {
        fieldEl.removeClass('is-invalid');
    }
    let feedbackEl = $('[role="validation_' + fieldKey + '_feedback"]');
    feedbackEl.html('');
}

function deleteFile(event) {
    event.preventDefault();

    let editFormEl = $('#edit_form');
    let preLoaderEl = $('#edit_pre_loader');

    showPreLoader(preLoaderEl);

    let formData = new FormData(editFormEl[0]);

    requestAjax(window.route_file_delete, {
        id: formData.get('id'),
        _token: formData.get('_token')
    }, function (response) {
        hidePreLoader(preLoaderEl);
        if (response.success) {
            $('#file_edit').html($('#success_delete_feedback').html());
        }

    }, function () {
        hidePreLoader(preLoaderEl);

    }, {
        dataType: 'json'
    });

}
