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

function sendFormUpdate(formElement) {
    let preLoaderEl = $('#edit_pre_loader');
    showPreLoader(preLoaderEl);

    let formData = new FormData(formElement);
    requestAjax(window.route_file_update, formData,
        function (response) {
            hidePreLoader(preLoaderEl);
            let errors = response.errors && Object.keys(response.errors).length
                ? response.errors
                : {};
            catchFeedback('file_form', ['alt', 'title', 'description'], errors);
            if (response.success) {
                getPreview(formData.get('id'));
            }
        }, function () {
            hidePreLoader(preLoaderEl);
        }, {
            dataType: 'json',
            processData: false,
            contentType: false
        });
}

function getPreview(id) {
    let preLoaderEl = $('#preview_pre_loader');
    showPreLoader(preLoaderEl);
    requestAjax(window.route_file_preview, {
        id: id,
        _token: window.csrf_token
    }, function (response) {
        $('#file_preview').html(response);
        hidePreLoader(preLoaderEl);
    }, function () {
        hidePreLoader(preLoaderEl);
    }, {
        dataType: 'html'
    });
}

$(document).ready(function() {
    $('#file_form').on('submit', function (e) {
        e.preventDefault();
        sendFormUpdate(e.target);
    });
});
