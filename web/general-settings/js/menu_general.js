$('document').ready(function () {

    /*
     * click outside submenu should close them right away
     */

    $('html').click(function() {
        var topMenusToClose = ['contentMenuHomeGeneral', 'contentMenuMailGeneral', 'contentMenuUsersGeneral'];

        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'general_home') {
            $('#menuHomeOverview').css('background-color', '#eeeeee');
        } else
            $('#menuHomeOverview').css('background-color', '#6A8EB2');

        if (menuSelected == 'general_mail') {
            $('#menuMailGeneral').css('background-color', '#eeeeee');
        } else
            $('#menuMailGeneral').css('background-color', '#6A8EB2');

        if (menuSelected == 'general_user') {
            $('#menuUsersGeneral').css('background-color', '#eeeeee');
        } else
            $('#menuUsersGeneral').css('background-color', '#6A8EB2');

        if (menuSelected == 'general_overview') {
            $('#menuHomeGeneral').css('background-color', '#eeeeee');
        } else
            $('#menuHomeGeneral').css('background-color', '#6A8EB2');

        for (var i = 0; i < topMenusToClose.length; i++) {
            if ($('#' + topMenusToClose[i]).is(':visible')) {
                $('#' + topMenusToClose[i]).hide();
            }
        }
        $('#menu_top_user').css('background-color', '#003466');

        $('#contentUserHome').css('background-color', '#003466');

        var menuToClose = ['contentUserHomeGeneral'];
        for (var i = 0; i < menuToClose.length; i++) {
            if ($('#' + menuToClose[i]).is(':visible')) {
                $('#' + menuToClose[i]).hide();
            }
        }
    });

    $('#menuHomeOverview').mouseenter(function (event) {
        $('#menuHomeOverview').css('cursor', 'pointer');
    });

    $('#menuHomeGeneral').mouseenter(function (event) {
        $('#menuHomeGeneral').css('cursor', 'pointer');
    });

    $('#menuMailGeneral').mouseenter(function (event) {
        $('#menuMailGeneral').css('cursor', 'pointer');
    });

    $('#menuUsersGeneral').mouseenter(function (event) {
        $('#menuUsersGeneral').css('cursor', 'pointer');
    });

    $('#menuHomeGeneral').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        if ($('#contentMenuHomeGeneral').is(':visible') && $('#contentMenuHomeGeneral').html()) {
            $('#contentMenuHomeGeneral').hide();
            return
        }

        $('#menuHomeGeneral').css('background-color', '#cccccc');

        $('#contentMenuUsersGeneral').hide();
        $('#contentUserHomeGeneral').hide();
        $('#contentMenuMailGeneral').hide();

        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'general_user') {
            $('#menuUsersGeneral').css('background-color', '#eeeeee');
        } else
            $('#menuUsersGeneral').css('background-color', '#6A8EB2');

        if (menuSelected == 'general_mail') {
            $('#menuMailGeneral').css('background-color', '#eeeeee');
        } else
            $('#menuMailGeneral').css('background-color', '#6A8EB2');

        $('#contentMenuHomeGeneral').css('left',$('#menuHomeGeneral').position().left);
        $('#contentMenuHomeGeneral').css('top', $('#menuHomeGeneral').position().top + 28);
        $('#contentMenuHomeGeneral').css('z-index', '500');
        $('#contentMenuHomeGeneral').css('padding', '4px');

        $('#contentMenuHomeGeneral').css('position', 'absolute');
        $('#contentMenuHomeGeneral').css('width', '200px');
        $('#contentMenuHomeGeneral').css('background-color', '#ffffff');
        $('#contentMenuHomeGeneral').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentMenuHomeGeneral').show();
    });

    $('#menuMailGeneral').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        if ($('#contentMenuMailGeneral').is(':visible') && $('#contentMenuMailGeneral').html()) {
            $('#contentMenuMailGeneral').hide();
            return
        }

        $('#menuMailGeneral').css('background-color', '#cccccc');

        $('#contentMenuUsersGeneral').hide();
        $('#contentUserHomeGeneral').hide();
        $('#contentMenuHomeGeneral').hide();

        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'general_user') {
            $('#menuUsersGeneral').css('background-color', '#eeeeee');
        } else
            $('#menuUsersGeneral').css('background-color', '#6A8EB2');

        if (menuSelected == 'general_overview') {
            $('#menuHomeGeneral').css('background-color', '#eeeeee');
        } else
            $('#menuHomeGeneral').css('background-color', '#6A8EB2');

        $('#contentMenuMailGeneral').css('left',$('#menuMailGeneral').position().left);
        $('#contentMenuMailGeneral').css('top', $('#menuMailGeneral').position().top + 28);
        $('#contentMenuMailGeneral').css('z-index', '500');
        $('#contentMenuMailGeneral').css('padding', '4px');

        $('#contentMenuMailGeneral').css('position', 'absolute');
        $('#contentMenuMailGeneral').css('width', '200px');
        $('#contentMenuMailGeneral').css('background-color', '#ffffff');
        $('#contentMenuMailGeneral').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentMenuMailGeneral').show();
    });

    $('#menuUsersGeneral').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        if ($('#contentMenuUsersGeneral').is(':visible') && $('#contentMenuMailGeneral').html()) {
            $('#contentMenuUsersGeneral').hide();
            return
        }

        $('#menuUsersGeneral').css('background-color', '#cccccc');

        $('#contentMenuMailGeneral').hide();
        $('#contentUserHomeGeneral').hide();
        $('#contentMenuHomeGeneral').hide();

        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'general_mail') {
            $('#menuMailGeneral').css('background-color', '#eeeeee');
        } else
            $('#menuMailGeneral').css('background-color', '#6A8EB2');

        if (menuSelected == 'general_overview') {
            $('#menuHomeGeneral').css('background-color', '#eeeeee');
        } else
            $('#menuHomeGeneral').css('background-color', '#6A8EB2');

        $('#contentMenuUsersGeneral').css('left',$('#menuUsersGeneral').position().left);
        $('#contentMenuUsersGeneral').css('top', $('#menuUsersGeneral').position().top + 28);
        $('#contentMenuUsersGeneral').css('z-index', '500');
        $('#contentMenuUsersGeneral').css('padding', '4px');

        $('#contentMenuUsersGeneral').css('position', 'absolute');
        $('#contentMenuUsersGeneral').css('width', '200px');
        $('#contentMenuUsersGeneral').css('background-color', '#ffffff');
        $('#contentMenuUsersGeneral').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentMenuUsersGeneral').show();
    });
});