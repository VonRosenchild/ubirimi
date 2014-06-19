$('document').ready(function () {

    /*
     * click outside submenu should close them right away
     */

    $('html').click(function() {
        var topMenusToClose = ['contentMenuAdminProjects', 'contentMenuAdminUsers', 'contentMenuAdminIssues', 'contentMenuAdminSystem', 'contentMenuUserMenu', 'contentMenuAdminHelpdeskOrganizations'];

        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'user') {
            $('#menuAdminUsers').css('background-color', '#eeeeee');
        } else
            $('#menuAdminUsers').css('background-color', '#6A8EB2');

        if (menuSelected == 'project') {
            $('#menuAdminProjects').css('background-color', '#eeeeee');
        } else
            $('#menuAdminProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'issue') {
            $('#menuAdminIssues').css('background-color', '#eeeeee');
        } else
            $('#menuAdminIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'system') {
            $('#menuAdminSystem').css('background-color', '#eeeeee');
        } else
            $('#menuAdminSystem').css('background-color', '#6A8EB2');

        if (menuSelected == 'helpdesk_organizations') {
            $('#menuAdminHelpdeskOrganizations').css('background-color', '#eeeeee');
        } else
            $('#menuAdminHelpdeskOrganizations').css('background-color', '#6A8EB2');

        for (var i = 0; i < topMenusToClose.length; i++) {
            if ($('#' + topMenusToClose[i]).is(':visible')) {
                $('#' + topMenusToClose[i]).hide();
            }
        }
        $('#menu_top_userAdmin').css('background-color', '#003466');

        var menuToClose = ['contentMenuUserMenu'];
        for (var i = 0; i < menuToClose.length; i++) {
            if ($('#' + menuToClose[i]).is(':visible')) {
                $('#' + menuToClose[i]).hide();
            }
        }
    });

    $('#menuAdminProjects').mouseenter(function (event) {
        $('#menuAdminProjects').css('cursor', 'pointer');
    });

    $('#menuAdminUsers').mouseenter(function (event) {
        $('#menuAdminUsers').css('cursor', 'pointer');
    });

    $('#menuAdminIssues').mouseenter(function (event) {
        $('#menuAdminIssues').css('cursor', 'pointer');
    });

    $('#menuAdminSystem').mouseenter(function (event) {
        $('#menuAdminSystem').css('cursor', 'pointer');
    });

    $('#menuAdminSystem').mouseenter(function (event) {
        $('#menuAdminSystem').css('cursor', 'pointer');
    });

    $('#menuAdminHelpdeskOrganizations').mouseenter(function (event) {
        $('#menuAdminHelpdeskOrganizations').css('cursor', 'pointer');
    });

    $('#menuAdminProjects').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        if ($('#contentMenuAdminProjects').is(':visible') && $('#contentMenuAdminProjects').html()) {
            $('#contentMenuAdminProjects').hide();
            return
        }

        $('#menuAdminProjects').css('background-color', '#cccccc');
        $('#menu_top_userAdmin').css('background-color', '#003466');

        $('#contentMenuAdminUsers').hide();
        $('#contentMenuAdminIssues').hide();
        $('#contentMenuAdminSystem').hide();
        $('#contentMenuUserMenu').hide();

        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'issue') {
            $('#menuAdminIssues').css('background-color', '#eeeeee');
        } else
            $('#menuAdminIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'user') {
            $('#menuAdminUsers').css('background-color', '#eeeeee');
        } else
            $('#menuAdminUsers').css('background-color', '#6A8EB2');

        if (menuSelected == 'administration') {
            $('#menuAdminHome').css('background-color', '#eeeeee');
        } else
            $('#menuAdminHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'system') {
            $('#menuAdminSystem').css('background-color', '#eeeeee');
        } else
            $('#menuAdminSystem').css('background-color', '#6A8EB2');

        $('#contentMenuAdminProjects').css('left',$('#menuAdminProjects').position().left);
        $('#contentMenuAdminProjects').css('top', $('#menuAdminProjects').position().top + 28);
        $('#contentMenuAdminProjects').css('z-index', '500');
        $('#contentMenuAdminProjects').css('padding', '4px');

        $('#contentMenuAdminProjects').css('position', 'absolute');
        $('#contentMenuAdminProjects').css('width', '200px');
        $('#contentMenuAdminProjects').css('background-color', '#ffffff');
        $('#contentMenuAdminProjects').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentMenuAdminProjects').show();
    });

    $('#menuAdminUsers').click(function (event) {

        event.stopPropagation();
        event.preventDefault();
        if ($('#contentMenuAdminUsers').is(':visible') && $('#contentMenuAdminUsers').html()) {
            $('#contentMenuAdminUsers').hide();
            return
        }

        $('#menuAdminUsers').css('background-color', '#cccccc');
        $('#menu_top_userAdmin').css('background-color', '#003466');

        $('#contentMenuAdminProjects').hide();
        $('#contentMenuAdminIssues').hide();
        $('#contentMenuAdminSystem').hide();
        $('#contentMenuUserMenu').hide();

        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'project') {
            $('#menuAdminProjects').css('background-color', '#eeeeee');
        } else
            $('#menuAdminProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'issue') {
            $('#menuAdminIssues').css('background-color', '#eeeeee');
        } else
            $('#menuAdminIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'administration') {
            $('#menuAdminHome').css('background-color', '#eeeeee');
        } else
            $('#menuAdminHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'system') {
            $('#menuAdminSystem').css('background-color', '#eeeeee');
        } else
            $('#menuAdminSystem').css('background-color', '#6A8EB2');

        $('#contentMenuAdminUsers').css('left',$('#menuAdminUsers').position().left);
        $('#contentMenuAdminUsers').css('top', $('#menuAdminUsers').position().top + 28);
        $('#contentMenuAdminUsers').css('z-index', '500');
        $('#contentMenuAdminUsers').css('padding', '4px');
        $('#contentMenuAdminUsers').css('position', 'absolute');
        $('#contentMenuAdminUsers').css('width', '200px');
        $('#contentMenuAdminUsers').css('background-color', '#ffffff');
        $('#contentMenuAdminUsers').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentMenuAdminUsers').show();

    });

    $('#menuAdminIssues').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        if ($('#contentMenuAdminIssues').is(':visible') && $('#contentMenuAdminIssues').html()) {
            $('#contentMenuAdminIssues').hide();
            return
        }

        $('#menuAdminIssues').css('background-color', '#cccccc');
        $('#menu_top_userAdmin').css('background-color', '#003466');

        $('#contentMenuAdminProjects').hide();
        $('#contentMenuAdminUsers').hide();
        $('#contentMenuAdminSystem').hide();
        $('#contentMenuUserMenu').hide();

        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'user') {
            $('#menuAdminUsers').css('background-color', '#eeeeee');
        } else
            $('#menuAdminUsers').css('background-color', '#6A8EB2');

        if (menuSelected == 'project') {
            $('#menuAdminProjects').css('background-color', '#eeeeee');
        } else
            $('#menuAdminProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'administration') {
            $('#menuAdminHome').css('background-color', '#eeeeee');
        } else
            $('#menuAdminHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'system') {
            $('#menuAdminSystem').css('background-color', '#eeeeee');
        } else
            $('#menuAdminSystem').css('background-color', '#6A8EB2');

        var menuElement = $('#contentMenuAdminIssues');
        menuElement.css('left',$('#menuAdminIssues').position().left);
        menuElement.css('top', $('#menuAdminIssues').position().top + 28);
        menuElement.addClass('ubirimiMenu');
        menuElement.show();
    });

    $('#menuAdminSystem').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        if ($('#contentMenuAdminSystem').is(':visible') && $('#contentMenuAdminSystem').html()) {
            $('#contentMenuAdminSystem').hide();
            return
        }

        $('#menuAdminSystem').css('background-color', '#cccccc');
        $('#menu_top_userAdmin').css('background-color', '#003466');

        $('#contentMenuAdminProjects').hide();
        $('#contentMenuAdminUsers').hide();
        $('#contentMenuAdminIssues').hide();
        $('#contentMenuUserMenu').hide();

        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'user') {
            $('#menuAdminUsers').css('background-color', '#eeeeee');
        } else
            $('#menuAdminUsers').css('background-color', '#6A8EB2');

        if (menuSelected == 'project') {
            $('#menuAdminProjects').css('background-color', '#eeeeee');
        } else
            $('#menuAdminProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'issue') {
            $('#menuAdminIssues').css('background-color', '#eeeeee');
        } else
            $('#menuAdminIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'administration') {
            $('#menuAdminHome').css('background-color', '#eeeeee');
        } else
            $('#menuAdminHome').css('background-color', '#6A8EB2');

        $('#contentMenuAdminSystem').css('left',$('#menuAdminSystem').position().left);
        $('#contentMenuAdminSystem').css('top', $('#menuAdminSystem').position().top + 28);
        $('#contentMenuAdminSystem').css('z-index', '500');
        $('#contentMenuAdminSystem').css('padding', '4px');
        $('#contentMenuAdminSystem').css('position', 'absolute');
        $('#contentMenuAdminSystem').css('width', '200px');
        $('#contentMenuAdminSystem').css('background-color', '#ffffff');
        $('#contentMenuAdminSystem').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentMenuAdminSystem').show();
    });

    $('#menu_top_userAdmin').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        if ($('#contentMenuUserMenu').is(':visible') && $('#contentMenuUserMenu').html()) {
            $('#contentMenuUserMenu').hide();
            return
        }
        $('#menu_top_userAdmin').css('background-color', '#808080');

        $('#contentMenuAdminProjects').hide();
        $('#contentMenuAdminUsers').hide();
        $('#contentMenuAdminSystem').hide();
        $('#contentMenuAdminIssues').hide();

        var menuSelected = $('#menu_selected').val();
        if (menuSelected == 'issue') {
            $('#menuAdminIssues').css('background-color', '#eeeeee');
        } else
            $('#menuAdminIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'user') {
            $('#menuAdminUsers').css('background-color', '#eeeeee');
        } else
            $('#menuAdminUsers').css('background-color', '#6A8EB2');

        if (menuSelected == 'project') {
            $('#menuAdminProjects').css('background-color', '#eeeeee');
        } else
            $('#menuAdminProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'administration') {
            $('#menuAdminHome').css('background-color', '#eeeeee');
        } else
            $('#menuAdminHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'system') {
            $('#menuAdminSystem').css('background-color', '#eeeeee');
        } else
            $('#menuAdminSystem').css('background-color', '#6A8EB2');

        $('#contentMenuUserMenu').css('left', $('#menu_top_userAdmin').position().left);
        $('#contentMenuUserMenu').css('top', $('#menu_top_userAdmin').position().top + 44);
        $('#contentMenuUserMenu').css('z-index', '500');
        $('#contentMenuUserMenu').css('padding', '4px');

        $('#contentMenuUserMenu').css('position', 'absolute');
        $('#contentMenuUserMenu').css('width', '140px');
        $('#contentMenuUserMenu').css('background-color', '#ffffff');
        $('#contentMenuUserMenu').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentMenuUserMenu').show();
    });

    // ****************************** HELPDESK *****************************************/

    $('#menuAdminHelpdeskOrganizations').click(function (event) {
        event.stopPropagation();
        event.preventDefault();

        if ($('#contentMenuAdminHelpdeskOrganizations').is(':visible') && $('#contentMenuAdminHelpdeskOrganizations').html()) {
            $('#contentMenuAdminHelpdeskOrganizations').hide();
            return
        }

        $('#menuAdminHelpdeskOrganizations').css('background-color', '#cccccc');
        $('#menu_top_userAdmin').css('background-color', '#003466');

        var menuSelected = $('#menu_selected').val();

        var menuElement = $('#contentMenuAdminHelpdeskOrganizations');
        menuElement.css('left', $('#menuAdminHelpdeskOrganizations').position().left);
        menuElement.css('top', $('#menuAdminHelpdeskOrganizations').position().top + 28);
        menuElement.addClass('ubirimiMenu');
        menuElement.show();
    });
});