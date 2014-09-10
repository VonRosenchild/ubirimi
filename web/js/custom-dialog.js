function applyStylesDialog() {
    $('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button').each(function(index, element) {
        $(element).removeClass('ui-button ui-widget ui-state-default ui-state-focus ui-state-hover ui-corner-all ui-button-text-only');
        $(element).removeAttr('role aria-disabled');
        $(element).blur();
    });

    $('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button').each(function(index, element) {
        $(element).addClass('btn ubirimi-btn');
    });
}

$.extend($.ui.dialog.prototype.options, {
    modal: true,
    resizable: false,
    draggable: false,
    dialogClass: "ubirimi-dialog",
    autoOpen: false,
    closeOnEscape: true,
    width: "auto",
    height: "auto",
    maxHeight: $(window).height() - 50,
    stack: true,
    position: {
        my: "center",
        at: "center",
        of: window
    },
    open: function(event, ui) {
        applyStylesDialog();
        $('.ui-dialog').focus();
    }
});