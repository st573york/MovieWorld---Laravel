/**
 * Vote events, functions
 */

function processVote(obj) {
    obj['sort_by'] = sort_by;

    $.ajax({
        type: "POST",
        url: "/movies/" + obj.userid + "/ajax/movie/vote",
        data: obj,
        cache: false,
        success: function (data) {
            updateMovies(data);
        },
        error: function (e, jqxhr) {
            alert('An error occurred: ' + jqxhr);
        }
    });
}