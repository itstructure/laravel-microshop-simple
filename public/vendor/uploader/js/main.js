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

function catchFeedback(rootId, fields, errors) {
    for (let i in fields) {
        let fieldKey = fields[i];
        let fieldEl = $('#' + rootId + ' #' + fieldKey);
        let feedbackEl = $('#validation_' + fieldKey + '_feedback');
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
