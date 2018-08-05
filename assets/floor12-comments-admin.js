$(document).on('change', 'form.f12-form-autosubmit', function () {
    submitForm($(this));
})

$(document).on('keyup', 'form.f12-form-autosubmit', function () {
    submitForm($(this));
})

function submitForm(form) {
    method = form.attr('method');
    action = form.attr('action');
    container = form.data('container');
    $.pjax.reload({
        url: action,
        method: method,
        container: container,
        data: form.serialize()
    })
}


function commentApprove(id) {
    $.ajax({
        url: f12CommentApproveUrl + "?id=" + id,
        method: 'POST',
        success: function (response) {
            info(response, 1);
            $.pjax.reload({
                container: '#items'
            });
        },
        error: function (response) {
            processError(response)
        }
    });

}