/**
 * Sort events, functions
 */

var sort_by = 'sort_by_date_most_recent';

jQuery(function ($) {

    // Highlight default date most recent sort by option
    $('.movie_actions_panel .dropdown-menu').find('a#' + sort_by).addClass('active');

    // Highlight sort by option
    $('.movie_actions_panel .dropdown-menu a').on('click', function () {
        $(this).parent().find('a').removeClass('active');
        $(this).addClass('active');
    });

});

function sortMovies(obj) {
    $.ajax({
        method: "GET",
        url: "/ajax/movie/sort",
        data: obj,
        cache: false,
        success: function (data) {
            updateMovies(data);

            if (obj.action != 'sort_by_test') {
                sort_by = obj.action;
            }
        },
        error: function (e, jqxhr) {
            alert('An error occurred: ' + jqxhr);
        }
    });
}