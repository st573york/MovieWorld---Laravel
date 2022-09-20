/**
 * Review events, functions
 */

function showReviewDialog(obj) {
    var buttons =
        [
            {
                'text': 'Close',
                'class': 'btn-secondary',
                'click': function () {
                    closePopupDialog('process_review');
                }
            },
            {
                'text': 'OK',
                'class': 'btn-primary',
                'click': function () {
                    obj['popupDialogData'] = $('#popup-dialog-form').serialize();

                    resetErrors();
                    validateReviewDialog(obj);
                },
            }
        ];

    $('#' + popupDialogPrefix + 'process_review')
        .closest('.ui-dialog')
        .children('.ui-dialog-titlebar')
        .css('background', 'limegreen');

    popupDialog({
        'id': 'process_review',
        'title': obj.title,
        'html': obj.html,
        'buttons': buttons
    });
}

function validateReviewDialog(obj) {
    $.ajax({
        type: "GET",
        url: "/movies/" + obj.userid + "/ajax/review/validate",
        data: obj,
        dataType: 'json',
        cache: false,
        success: function (data) {
            if (data.resp == 'success') {
                processReview(obj);
            }
            else {
                $.each(data, function (key, val) {
                    $('.popup-dialog-container #' + key).addClass('invalid').parent().next('.invalid_message').html(val);
                });
            }
        },
        error: function (e, jqxhr) {
            alert('An error occurred: ' + jqxhr);
        }
    });
}

function processReview(obj) {
    obj['sort_by'] = sort_by;

    $.ajax({
        type: "POST",
        url: "/movies/" + obj.userid + "/ajax/review/store",
        data: obj,
        cache: false,
        success: function (data) {
            closePopupDialog('process_review');

            updateMovies(data);
        },
        error: function (e, jqxhr) {
            alert('An error occurred: ' + jqxhr);
        }
    });
}