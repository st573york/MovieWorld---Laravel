/**
 * Popup-Dialog Widget
 */

var popupDialogPrefix = 'popup-dialog-';

jQuery(function ($) {

    $(window).on('resize', function () {
        centerPopupDialogs();
    });

});

function getPopupDialogs() {
    var ids = '';
    for (var i = 0; i < dialogs.length; i++) {
        ids += '#' + popupDialogPrefix + dialogs[i] + ((i < dialogs.length - 1) ? ', ' : '');
    }
    return ids;
}

function initPopupDialogs() {
    $(getPopupDialogs()).dialog({
        autoOpen: false,
        draggable: false,
        resizable: false,
        modal: true,
        width: 'auto',
        open: function () {
            // Remove focus from dialog inputs
            $(this).parent().trigger('focus');
        },
        close: function () {
            $('.popup-dialog-container').remove();
        }
    });
}

function popupDialog(settings) {
    /* Default options */
    var options = $.extend({
        id: '',
        title: '',
        html: '',
        buttons: []
    }, settings);

    processPopupDialog(options);
}

function processPopupDialog(options) {
    $('#' + popupDialogPrefix + options.id)
        .data(options)
        .html(options.html)
        .dialog('option',
            {
                'title': options.title,
                'buttons': options.buttons
            });

    if (!$('#' + popupDialogPrefix + options.id).dialog('isOpen')) {
        $('#' + popupDialogPrefix + options.id).dialog('open');
    }
}

function closePopupDialog(id) {
    $('#' + popupDialogPrefix + id).dialog('close');
}

function centerPopupDialogs() {
    $(getPopupDialogs()).dialog('option', 'position', { my: 'center', at: 'center', of: window });
}

function resetErrors() {
    $('.popup-dialog-container').find('input, textarea').removeClass('invalid');
    $('.popup-dialog-container .invalid_message').html('');
}