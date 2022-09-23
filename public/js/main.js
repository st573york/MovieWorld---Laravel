/**
 * Generic events, functions
 */

var requestTimer = false;
var loader = false;

jQuery(function ($) {

    // Show loader when ajax request begins
    $(this).ajaxStart(function () {
        if (loader) {
            $('#loader').show();
        }
    });

    // Hide loader when ajax request has completed
    $(this).ajaxStop(function () {
        if (loader) {
            $('#loader').hide();

            loader = false;
        }
    });

    // Trigger ajax on search input change                                                                                                                                                                               
    $($('input[type="search"]')).on('input propertychange', function () {
        $('.add_sort_movies .dropdown-menu a').removeClass('active');

        if (requestTimer) {
            window.clearTimeout(requestTimer);
            requestTimer = false;
        }

        var obj = {};
        obj['action'] = 'sort_by_text';
        obj['searchtext'] = this.value;

        requestTimer = setTimeout(function () { loader = true; sortMovies(obj); }, 500);
    });

    // Scroll to top when dropdown menu is visible
    $('.dropdown-link').on('click', function () {
        $('.dropdown-menu').animate({ scrollTop: 0 });
    });

});