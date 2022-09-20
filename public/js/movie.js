/**
 * Movie events, functions
 */

function showMovieDialog(obj) {
    var buttons =
        [
            {
                'text': 'Close',
                'class': 'btn-secondary',
                'click': function () {
                    closePopupDialog('process_movie');
                }
            },
            {
                'text': 'OK',
                'class': 'btn-primary',
                'click': function () {
                    obj['popupDialogData'] = $('#popup-dialog-form').serialize();

                    resetErrors();
                    validateMovieDialog(obj);
                },
            }
        ];

    $('#' + popupDialogPrefix + 'process_movie')
        .closest('.ui-dialog')
        .children('.ui-dialog-titlebar')
        .css('background', 'limegreen');

    popupDialog({
        'id': 'process_movie',
        'title': obj.title,
        'html': obj.html,
        'buttons': buttons
    });
}

function validateMovieDialog(obj) {
    $.ajax({
        type: "GET",
        url: "/movies/" + obj.userid + "/ajax/movie/validate",
        data: obj,
        dataType: 'json',
        cache: false,
        success: function (data) {
            if (data.resp == 'success') {
                processMovie(obj);
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

function confirmMovieDeletion(obj) {
    var buttons =
        [
            {
                'text': 'Close',
                'class': 'btn-secondary',
                'click': function () {
                    closePopupDialog('confirm');
                }
            },
            {
                'text': 'OK',
                'class': 'btn-primary',
                'click': function () {
                    processMovie(obj);
                },
            }
        ];

    $('#' + popupDialogPrefix + 'confirm')
        .closest('.ui-dialog')
        .children('.ui-dialog-titlebar')
        .css('background', '#d92');

    popupDialog({
        'id': 'confirm',
        'title': 'Delete Movie',
        'html': obj.html,
        'buttons': buttons
    });
}

function processMovie(obj) {
    obj['sort_by'] = sort_by;

    $.ajax({
        type: "POST",
        url: "/movies/" + obj.userid + "/ajax/movie/" + obj.action,
        data: obj,
        cache: false,
        success: function (data) {
            if (obj.action == 'store' ||
                obj.action == 'update') {
                closePopupDialog('process_movie');
            }
            else if (obj.action == 'delete') {
                closePopupDialog('confirm');
            }

            updateMovies(data);
        },
        error: function (e, jqxhr) {
            alert('An error occurred: ' + jqxhr);
        }
    });
}

function updateMovies(data) {
    // Update movies
    $('.movie_content').remove();
    if (data.movies_data.length) {
        $('.movie_container').append('<div class="movie_content">' + data.movies_data + '</div>');
    }

    // Update found movies
    $('.found_movies_count').html($('.movie_data').length);
}
