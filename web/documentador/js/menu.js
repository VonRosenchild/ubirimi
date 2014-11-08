$('document').ready(function () {

    /*
     * click outside submenu should close them right away
     */

    /*
     * click outside submenu should close them right away
     */
    $('#menuAdminDocHome').mouseenter(function (event) {
        $('#menuAdminDocHome').css('cursor', 'pointer');
    });
    $('#menuAdminDocConfiguration').mouseenter(function (event) {
        $('#menuAdminDocConfiguration').css('cursor', 'pointer');
    });
    $('#menuAdminDocUsersSecurity').mouseenter(function (event) {
        $('#menuAdminDocUsersSecurity').css('cursor', 'pointer');
    });
    $('#menuAdminDocSpaces').mouseenter(function (event) {
        $('#menuAdminDocSpaces').css('cursor', 'pointer');
    });
    $('#menuAdminDocLookFeel').mouseenter(function (event) {
        $('#menuAdminDocLookFeel').css('cursor', 'pointer');
    });
    $('#menuAdminDocSystem').mouseenter(function (event) {
        $('#menuAdminDocSystem').css('cursor', 'pointer');
    });

    $('#menuAdminHelpdeskOrganizations').mouseenter(function (event) {
        $('#menuAdminHelpdeskOrganizations').css('cursor', 'pointer');
    });

    $('html').click(function() {
        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'doc_administration') {
            $('#menuAdminDocHome').css('background-color', '#eeeeee');
        } else
            $('#menuAdminDocHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'doc_configuration') {
            $('#menuAdminDocConfiguration').css('background-color', '#eeeeee');
        } else
            $('#menuAdminDocConfiguration').css('background-color', '#6A8EB2');

        if (menuSelected == 'doc_spaces') {
            $('#menuAdminSpaces').css('background-color', '#eeeeee');
        } else
            $('#menuAdminSpaces').css('background-color', '#6A8EB2');

        if (menuSelected == 'doc_users') {
            $('#menuAdminDocUsersSecurity').css('background-color', '#eeeeee');
        } else
            $('#menuAdminDocUsersSecurity').css('background-color', '#6A8EB2');

        if (menuSelected == 'doc_look_feel') {
            $('#menuAdminDocLookFeel').css('background-color', '#eeeeee');
        } else
            $('#menuAdminDocLookFeel').css('background-color', '#6A8EB2');

        if (menuSelected == 'doc_system') {
            $('#menuAdminDocSystem').css('background-color', '#eeeeee');
        } else
            $('#menuAdminDocSystem').css('background-color', '#6A8EB2');

        var topMenusToClose = ['contentMenuDocumentador', 'contentMenuAdminDocConfiguration', 'contentMenuAdminSpaces', 'contentMenuAdminDocUsersSecurity', 'contentMenuAdminDocLookFeel', 'contentMenuAdminDocSystem', 'contentMenuUserMenu'];
        for (var i = 0; i < topMenusToClose.length; i++) {
            if ($('#' + topMenusToClose[i]).is(':visible')) {
                $('#' + topMenusToClose[i]).hide();
            }
        }
        $('#menu_top_user').css('background-color', '#003466');

        var menuToClose = ['menu_child_pages', 'menu_page_tools', 'contentUserHome'];
        for (var i = 0; i < menuToClose.length; i++) {
            if ($('#' + menuToClose[i]).is(':visible')) {
                $('#' + menuToClose[i]).hide();
            }
        }
    });

    $('#menuAdminDocUsersSecurity').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        if ($('#contentMenuAdminDocUsersSecurity').is(':visible') && $('#contentMenuAdminDocUsersSecurity').html()) {
            $('#contentMenuAdminDocUsersSecurity').hide();
            return
        }

        $('#menu_top_userAdmin').css('background-color', '#003466');
        $('#menuAdminDocUsersSecurity').css('background-color', '#cccccc');

        $('#contentMenuAdminDocSpaces').hide();

        var menuSelected = $('#menu_selected').val();
        if (menuSelected == 'doc_spaces') {
            $('#menuAdminDocSpaces').css('background-color', '#eeeeee');
        } else
            $('#menuAdminDocSpaces').css('background-color', '#6A8EB2');

        $('#contentMenuAdminDocUsersSecurity').css('left',$('#menuAdminDocUsersSecurity').position().left)
        $('#contentMenuAdminDocUsersSecurity').css('top', $('#menuAdminDocUsersSecurity').position().top + 28)
        $('#contentMenuAdminDocUsersSecurity').css('z-index', '500');
        $('#contentMenuAdminDocUsersSecurity').css('padding', '4px');

        $('#contentMenuAdminDocUsersSecurity').css('position', 'absolute');
        $('#contentMenuAdminDocUsersSecurity').css('width', '200px');
        $('#contentMenuAdminDocUsersSecurity').css('background-color', '#ffffff');
        $('#contentMenuAdminDocUsersSecurity').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentMenuAdminDocUsersSecurity').show();
    });

    $('#menuAdminDocSpaces').click(function (event) {

        event.stopPropagation();
        event.preventDefault();

        if ($('#contentMenuAdminDocSpaces').is(':visible') && $('#contentMenuAdminDocSpaces').html()) {
            $('#contentMenuAdminDocSpaces').hide();
            return
        }

        $('#contentMenuAdminDocUsersSecurity').hide();
        $('#menuAdminDocSpaces').css('background-color', '#cccccc');

        var menuSelected = $('#menu_selected').val();
        if (menuSelected == 'doc_users') {
            $('#menuAdminDocUsersSecurity').css('background-color', '#eeeeee');
        } else
            $('#menuAdminDocUsersSecurity').css('background-color', '#6A8EB2');

        $('#menu_top_userAdmin').css('background-color', '#003466');

        $('#contentMenuAdminDocSpaces').css('left',$('#menuAdminDocSpaces').position().left)
        $('#contentMenuAdminDocSpaces').css('top', $('#menuAdminDocSpaces').position().top + 28)
        $('#contentMenuAdminDocSpaces').css('z-index', '500');
        $('#contentMenuAdminDocSpaces').css('padding', '4px');

        $('#contentMenuAdminDocSpaces').css('position', 'absolute');
        $('#contentMenuAdminDocSpaces').css('width', '200px');
        $('#contentMenuAdminDocSpaces').css('background-color', '#ffffff');
        $('#contentMenuAdminDocSpaces').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentMenuAdminDocSpaces').show();
    });
    $('#menuDocumentador').click(function (event) {
        event.preventDefault();
        event.stopPropagation();

        if ($('#contentMenuDocumentador').is(':visible') && $('#contentMenuDocumentador').html()) {
            $('#contentMenuDocumentador').hide();
            return
        }

        $('#menuDocumentador').css('background-color', '#cccccc');
        $('#menu_top_user').css('background-color', '#003466');

        $('#contentMenuHome').hide();
        $('#contentUserHome').hide();
        $('#menu_child_pages').hide();
        $('#menu_page_tools').hide();

        $.ajax({
            type: "POST",
            url: '/menu/documentator-menu',
            data: {
            },
            success: function (response) {
                $('#contentMenuDocumentador').html(response);
                $('#contentMenuDocumentador').css('left',$('#menuDocumentador').position().left)
                $('#contentMenuDocumentador').css('top', $('#menuDocumentador').position().top + 28)
                $('#contentMenuDocumentador').css('z-index', '500');
                $('#contentMenuDocumentador').css('padding', '4px');

                $('#contentMenuDocumentador').css('position', 'absolute');
                $('#contentMenuDocumentador').css('width', '200px');
                $('#contentMenuDocumentador').css('background-color', '#ffffff');
                $('#contentMenuDocumentador').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
                $('#contentMenuDocumentador').show();
            }
        });
    });
});