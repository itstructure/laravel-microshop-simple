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
        let fieldName = fields[i];
        let fieldEl = $('#' + rootId + ' [name="data[' + fieldName + ']"]');
        let feedbackEl = $('#validation_' + fieldName + '_feedback');
        if (errors[fieldName] !== undefined) {
            if (!fieldEl.hasClass('is-invalid')) {
                fieldEl.addClass('is-invalid');
            }
            feedbackEl.html(errors[fieldName]);
        } else {
            if (fieldEl.hasClass('is-invalid')) {
                fieldEl.removeClass('is-invalid');
            }
            feedbackEl.html('');
        }
    }
}
