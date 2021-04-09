var f12Comments = {

    formUrl: null,

    deleteUrl: null,

    indexUrl: null,

    updateUrl: null,

    approveUrl: null,

    commentBackup: null,

    newComment: function (event) {
        let params = $(event.target).parents('div.f12-comments').data('params');
        this.loadForm(params);
    },

    loadForm: function (params) {
        $('form.f12-comments-form').remove();

        var block = $(params.block_id);

        $.ajax({
            url: f12Comments.formUrl,
            data: params,
            success: function (response) {
                block.html(response);
            },
            error: function (response) {
                processError(response);
            }
        });
    },
    expand: (event) => {
        $(event.target).parents('.f12-comments').find('.object-comments').addClass('opened');
    },
    loadList: function (blockId) {
        var block = $(blockId);
        classname = block.data('classname');
        object_id = block.data('object_id');
        $.ajax({
            url: f12Comments.indexUrl,
            data: {classname: classname, object_id: object_id},
            success: function (response) {
                block.html(response);
            },
            error: function (response) {
                processError(response);
            }
        });
    },

    delete: function (id) {
        if (!confirm('Are you sure?'))
            return false;

        $.ajax({
            method: 'DELETE',
            data: {id: id},
            url: f12Comments.deleteUrl,
            success: function (response) {
                $('div.f12-comment[data-key="' + id + '"]').fadeOut(300);
            },
            error: function (response) {
                processError(response);
            }

        })
    },

    edit: function (id) {
        $.ajax({
            data: {id: id},
            url: f12Comments.updateUrl,
            success: function (response) {
                f12Comments.commentBackup = $('div.f12-comment[data-key="' + id + '"]').html();
                $('div.f12-comment[data-key="' + id + '"]').html(response);
            },
            error: function (response) {
                processError(response);
            }

        })
    },

    approve: function (id) {
        $.ajax({
            url: f12Comments.approveUrl + "?id=" + id,
            method: 'POST',
            success: function (response) {
                info(response, 1);
                $('div.f12-comment[data-key="' + id + '"]').removeClass('f12-pending');
            },
            error: function (response) {
                processError(response)
            }
        });

    }

}


$(document).on('click', '.f12-comment-button-answer', function () {
    id = $(this).parents('.f12-comment').data('key');
    $.ajax()
});

$(document).on('submit', '.f12-comments-form', function (event) {
    event.preventDefault();
    form = $(this);
    form.find('button[type=submit]').prop('disabled', true);
    action = form.attr('action');
    $.ajax({
        method: 'POST',
        data: form.serialize(),
        url: action,
        success: function (response) {
            id = form.parents('.f12-comments').find('.f12-comment-list').attr('id');
            f12Comments.loadList('#' + id);
            form.html('');
            info(response, 1);
            setTimeout(function () {
                form.fadeOut(500);
            }, 2000)
        },
        error: function (response) {
            form.find('button[type=submit]').prop('disabled', false);
            processError(response);
        }

    })
});
