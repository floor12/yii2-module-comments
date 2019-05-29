var f12CommentFormUrl;
var f12CommentDeleteUrl;
var f12CommentIndexUrl;

function f12CommentsLoadForm(event) {
    $('form.f12-comments-form').remove();

    let params = $(event.target).parents('div.f12-comments').data('params');

    var block = $(params.block_id);

    $.ajax({
        url: f12CommentFormUrl,
        data: params,
        success: function (response) {
            block.html(response);
        },
        error: function (response) {
            processError(response);
        }
    });
}


function f12CommentsLoadList(blockId) {
    var block = $(blockId);
    classname = block.data('classname');
    object_id = block.data('object_id');
    $.ajax({
        url: f12CommentIndexUrl,
        data: {classname: classname, object_id: object_id},
        success: function (response) {
            block.html(response);
        },
        error: function (response) {
            processError(response);
        }
    });
}


$(document).on('click', '.f12-comment-button-answer', function () {
    id = $(this).parents('.f12-comment').data('key');
    $.ajax()
});

$(document).on('submit', '.f12-comments-form', function (event) {
    event.preventDefault();
    form = $(this);
    action = form.attr('action');
    $.ajax({
        method: 'POST',
        data: form.serialize(),
        url: action,
        success: function (response) {
            id = form.parents('.f12-comments').find('.f12-comment-list').attr('id');
            f12CommentsLoadList('#' + id);
            form.html('');
            info(response, 1);
            setTimeout(function () {
                form.fadeOut(500);
            }, 2000)
        },
        error: function (response) {
            processError(response);
        }

    })
});
