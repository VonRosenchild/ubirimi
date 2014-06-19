$('document').ready(function () {

    $('#menuUserSummaryFilters').click(function (event) {
        event.stopPropagation();
        event.preventDefault();

        $('#contentUserHome').hide();
        $('#contentMenuIssues').hide();
        $('#contentMenuProjects').hide();
        $('#contentMenuSVN').hide();
        $('#contentMenuHome').hide();
        $('#contentUserHome').hide();
        $('#contentMenuFilters').hide();
        $('#contentMenuAgile').hide();

        if ($('#contentMenuUserSummaryFilters').is(':visible') && $('#contentMenuUserSummaryFilters').html()) {
            $('#contentMenuUserSummaryFilters').hide();

            return
        }

        $('#contentMenuUserSummaryFilters').css('left', $('#menuUserSummaryFilters').position().left);
        $('#contentMenuUserSummaryFilters').css('top', $('#menuUserSummaryFilters').position().top + 28);
        $('#contentMenuUserSummaryFilters').css('z-index', '500');
        $('#contentMenuUserSummaryFilters').css('padding', '4px');

        $('#contentMenuUserSummaryFilters').css('position', 'absolute');
        $('#contentMenuUserSummaryFilters').css('width', '140px');
        $('#contentMenuUserSummaryFilters').css('background-color', '#ffffff');
        $('#contentMenuUserSummaryFilters').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentMenuUserSummaryFilters').show();
    });

    if ($('#view_issue_workflow_menu_enabled')) {
        var enabled = $('#view_issue_workflow_menu_enabled').val();
        if (enabled == 1)
            $('#btnIssueWorkflow').addClass('button');
        else {
            $('#btnIssueWorkflow').addClass('button_disabled');
        }
    }

    $('#btnIssueMoreActions').click(function (event) {

        event.preventDefault();
        event.stopPropagation();

        $('#menu_workflow').hide();
        if ($('#menu_more_actions').is(':visible')) {
            $('#menu_more_actions').hide();

        } else {
            $('#menu_more_actions').css('left',$('#btnIssueMoreActions').position().left);
            $('#menu_more_actions').css('top', $('#btnIssueMoreActions').position().top + 32);
            $('#menu_more_actions').show();
        }

        $('#contentMenuIssues').hide();
        $('#contentMenuProjects').hide();
        $('#contentMenuFilters').hide();
        $('#contentMenuSVN').hide();
        $('#contentMenuHome').hide();
        $('#contentMenuHelpDesk').hide();
        $('#contentUserHome').hide();
        $('#contentMenuAgile').hide();

        var menuSelected = $('#menu_selected').val();
        if (menuSelected == 'issue') {
            $('#menuIssues').css('background-color', '#eeeeee');
        } else
            $('#menuIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'filters') {
            $('#menuFilters').css('background-color', '#eeeeee');
        } else
            $('#menuFilters').css('background-color', '#6A8EB2');

        if (menuSelected == 'project') {
            $('#menuProjects').css('background-color', '#eeeeee');
        } else
            $('#menuProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'home') {
            $('#menuHome').css('background-color', '#eeeeee');
        } else
            $('#menuHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'svn') {
            $('#menuSVN').css('background-color', '#eeeeee');
        } else
            $('#menuSVN').css('background-color', '#6A8EB2');

        if (menuSelected == 'agile') {
            $('#menuAgile').css('background-color', '#eeeeee');
        } else
            $('#menuAgile').css('background-color', '#6A8EB2');

        $('#menu_top_user').css('background-color', '#003466');
    });

    $('#btnSearchIssuesViewOptions').click(function (event) {

        event.preventDefault();
        event.stopPropagation();

        if ($('#menuIssueSearchViewOptionsContent').is(':visible')) {
            $('#menuIssueSearchViewOptionsContent').hide();
        } else {
            var leftOffset = (200 - $('#btnSearchIssuesViewOptions').width()) - 16;

            $('#menuIssueSearchViewOptionsContent').css('left', $('#btnSearchIssuesViewOptions').position().left - leftOffset);
            $('#menuIssueSearchViewOptionsContent').css('top', $('#btnSearchIssuesViewOptions').position().top + 32);
            $('#menuIssueSearchViewOptionsContent').show();
        }

        $('#contentMenuIssues').hide();
        $('#contentMenuProjects').hide();
        $('#contentMenuFilters').hide();
        $('#contentMenuSVN').hide();
        $('#contentMenuHome').hide();
        $('#contentUserHome').hide();
        $('#contentMenuAgile').hide();
        $('#contentMenuHelpDesk').hide();
        $('#menuIssueSearchOptionsContent').hide();

        var menuSelected = $('#menu_selected').val();
        if (menuSelected == 'issue') {
            $('#menuIssues').css('background-color', '#eeeeee');
        } else
            $('#menuIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'project') {
            $('#menuProjects').css('background-color', '#eeeeee');
        } else
            $('#menuProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'filters') {
            $('#menuFilters').css('background-color', '#eeeeee');
        } else
            $('#menuFilters').css('background-color', '#6A8EB2');

        if (menuSelected == 'home') {
            $('#menuHome').css('background-color', '#eeeeee');
        } else
            $('#menuHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'svn') {
            $('#menuSVN').css('background-color', '#eeeeee');
        } else
            $('#menuSVN').css('background-color', '#6A8EB2');

        if (menuSelected == 'agile') {
            $('#menuAgile').css('background-color', '#eeeeee');
        } else
            $('#menuAgile').css('background-color', '#6A8EB2');

        $('#menu_top_user').css('background-color', '#003466');
    });

    $('#btnSearchIssuesOptions').click(function (event) {

        if ($(this).hasClass('disabled')) {
            return
        }

        event.preventDefault();
        event.stopPropagation();

        if ($('#menuIssueSearchOptionsContent').is(':visible')) {
            $('#menuIssueSearchOptionsContent').hide();
        } else {
            var leftOffset = (200 - $('#btnSearchIssuesOptions').width()) - 16;

            $('#menuIssueSearchOptionsContent').css('left', $('#btnSearchIssuesOptions').position().left - leftOffset);
            $('#menuIssueSearchOptionsContent').css('top', $('#btnSearchIssuesOptions').position().top + 32);
            $('#menuIssueSearchOptionsContent').show();
        }

        $('#menuIssueSearchViewOptionsContent').hide();
        $('#contentMenuIssues').hide();
        $('#contentMenuProjects').hide();
        $('#contentMenuFilters').hide();
        $('#contentMenuSVN').hide();
        $('#contentMenuHelpDesk').hide();
        $('#contentMenuHome').hide();
        $('#contentUserHome').hide();
        $('#contentMenuAgile').hide();

        var menuSelected = $('#menu_selected').val();
        if (menuSelected == 'issue') {
            $('#menuIssues').css('background-color', '#eeeeee');
        } else
            $('#menuIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'project') {
            $('#menuProjects').css('background-color', '#eeeeee');
        } else
            $('#menuProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'filters') {
            $('#menuFilters').css('background-color', '#eeeeee');
        } else
            $('#menuFilters').css('background-color', '#6A8EB2');

        if (menuSelected == 'home') {
            $('#menuHome').css('background-color', '#eeeeee');
        } else
            $('#menuHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'svn') {
            $('#menuSVN').css('background-color', '#eeeeee');
        } else
            $('#menuSVN').css('background-color', '#6A8EB2');

        if (menuSelected == 'agile') {
            $('#menuAgile').css('background-color', '#eeeeee');
        } else
            $('#menuAgile').css('background-color', '#6A8EB2');

        $('#menu_top_user').css('background-color', '#003466');
    });

    $('#menuHelpDesk, #menuAgile, #menuFilters, #menuIssues, #menuProjects, #menuHome').mouseenter(function (event) {
        $(this).css('cursor', 'pointer');
    });

    $('#menuAgile').click(function (event) {
        event.preventDefault();
        event.stopPropagation();

        if ($('#contentMenuAgile').is(':visible') && $('#contentMenuAgile').html()) {
            $('#contentMenuAgile').hide();
            return
        }

        $('#menuAgile').css('background-color', '#cccccc');
        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'project') {
            $('#menuProjects').css('background-color', '#eeeeee');
        } else
            $('#menuProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'home') {
            $('#menuHome').css('background-color', '#eeeeee');
        } else
            $('#menuHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'svn') {
            $('#menuSVN').css('background-color', '#eeeeee');
        } else
            $('#menuSVN').css('background-color', '#6A8EB2');

        if (menuSelected == 'filters') {
            $('#menuFilters').css('background-color', '#eeeeee');
        } else
            $('#menuFilters').css('background-color', '#6A8EB2');

        if (menuSelected == 'issue') {
            $('#menuIssues').css('background-color', '#eeeeee');
        } else
            $('#menuIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'helpdesk') {
            $('#menuHelpDesk').css('background-color', '#eeeeee');
        } else
            $('#menuHelpDesk').css('background-color', '#6A8EB2');

        $('#menu_top_user').css('background-color', '#003466');

        $('#contentMenuProjects').hide();
        $('#contentMenuHome').hide();
        $('#contentMenuIssues').hide();
        $('#contentMenuSVN').hide();
        $('#contentMenuFilters').hide();
        $('#contentMenuHelpDesk').hide();
        $('#contentUserHome').hide();
        $('#contentMenuDocumentator').hide();
        $('#menu_more_actions').hide();
        $('#menu_workflow').hide();
        $('#menuIssueSearchViewOptionsContent').hide();
        $('#contentMenuUserSummaryFilters').hide();
        $('#menu_add_to_sprint').hide();

        $.ajax({
            type: "POST",
            url: '/menu/agile-menu',
            data: {
            },
            success: function (response) {
                $('#contentMenuAgile').html(response);
                $('#contentMenuAgile').css('left',$('#menuAgile').position().left);
                $('#contentMenuAgile').css('top', $('#menuAgile').position().top + 28);
                $('#contentMenuAgile').css('z-index', '500');
                $('#contentMenuAgile').css('padding', '4px');

                $('#contentMenuAgile').css('position', 'absolute');
                $('#contentMenuAgile').css('width', '200px');
                $('#contentMenuAgile').css('background-color', '#ffffff');
                $('#contentMenuAgile').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
                $('#contentMenuAgile').show();
            }
        });
    });

    $('#menuHelpDesk').click(function (event) {
        event.preventDefault();
        event.stopPropagation();

        if ($('#contentMenuHelpDesk').is(':visible') && $('#contentMenuHelpDesk').html()) {
            $('#contentMenuHelpDesk').hide();
            return
        }

        $('#menuHelpDesk').css('background-color', '#cccccc');
        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'project') {
            $('#menuProjects').css('background-color', '#eeeeee');
        } else
            $('#menuProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'home') {
            $('#menuHome').css('background-color', '#eeeeee');
        } else
            $('#menuHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'svn') {
            $('#menuSVN').css('background-color', '#eeeeee');
        } else
            $('#menuSVN').css('background-color', '#6A8EB2');

        if (menuSelected == 'filters') {
            $('#menuFilters').css('background-color', '#eeeeee');
        } else
            $('#menuFilters').css('background-color', '#6A8EB2');

        if (menuSelected == 'issue') {
            $('#menuIssues').css('background-color', '#eeeeee');
        } else
            $('#menuIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'agile') {
            $('#menuAgile').css('background-color', '#eeeeee');
        } else
            $('#menuAgile').css('background-color', '#6A8EB2');

        $('#menu_top_user').css('background-color', '#003466');

        $('#contentMenuProjects').hide();
        $('#contentMenuHome').hide();
        $('#contentMenuIssues').hide();
        $('#contentMenuSVN').hide();
        $('#contentMenuAgile').hide();
        $('#contentMenuFilters').hide();
        $('#contentUserHome').hide();
        $('#contentMenuDocumentator').hide();
        $('#menu_more_actions').hide();
        $('#menu_workflow').hide();
        $('#menuIssueSearchViewOptionsContent').hide();
        $('#contentMenuUserSummaryFilters').hide();
        $('#menu_add_to_sprint').hide();

        $.ajax({
            type: "POST",
            url: '/menu/helpdesk-menu',
            data: {
            },
            success: function (response) {
                $('#contentMenuHelpDesk').html(response);
                $('#contentMenuHelpDesk').css('left',$('#menuHelpDesk').position().left);
                $('#contentMenuHelpDesk').css('top', $('#menuHelpDesk').position().top + 28);
                $('#contentMenuHelpDesk').css('z-index', '500');
                $('#contentMenuHelpDesk').css('padding', '4px');

                $('#contentMenuHelpDesk').css('position', 'absolute');
                $('#contentMenuHelpDesk').css('width', '200px');
                $('#contentMenuHelpDesk').css('background-color', '#ffffff');
                $('#contentMenuHelpDesk').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
                $('#contentMenuHelpDesk').show();
            }
        });
    });

    $('#menuIssues').click(function (event) {
        event.preventDefault();
        event.stopPropagation();

        if ($('#contentMenuIssues').is(':visible') && $('#contentMenuIssues').html()) {
            $('#contentMenuIssues').hide();
            return
        }

        $('#menuIssues').css('background-color', '#cccccc');
        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'project') {
            $('#menuProjects').css('background-color', '#eeeeee');
        } else
            $('#menuProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'home') {
            $('#menuHome').css('background-color', '#eeeeee');
        } else
            $('#menuHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'svn') {
            $('#menuSVN').css('background-color', '#eeeeee');
        } else
            $('#menuSVN').css('background-color', '#6A8EB2');

        if (menuSelected == 'filters') {
            $('#menuFilters').css('background-color', '#eeeeee');
        } else
            $('#menuFilters').css('background-color', '#6A8EB2');

        if (menuSelected == 'agile') {
            $('#menuAgile').css('background-color', '#eeeeee');
        } else
            $('#menuAgile').css('background-color', '#6A8EB2');

        if (menuSelected == 'helpdesk') {
            $('#menuHelpDesk').css('background-color', '#eeeeee');
        } else
            $('#menuHelpDesk').css('background-color', '#6A8EB2');

        $('#menu_top_user').css('background-color', '#003466');

        $('#contentMenuProjects').hide();
        $('#contentMenuFilters').hide();
        $('#contentMenuHome').hide();
        $('#contentUserHome').hide();
        $('#contentMenuDocumentator').hide();
        $('#contentMenuSVN').hide();
        $('#menu_more_actions').hide();
        $('#menu_workflow').hide();
        $('#contentMenuHelpDesk').hide();
        $('#contentMenuAgile').hide();
        $('#menuIssueSearchViewOptionsContent').hide();
        $('#contentMenuUserSummaryFilters').hide();
        $('#menu_add_to_sprint').hide();

        $.ajax({
            type: "POST",
            url: '/menu/issues-menu',
            data: {
            },
            success: function (response) {
                $('#contentMenuIssues').html(response);
                $('#contentMenuIssues').css('left',$('#menuIssues').position().left);
                $('#contentMenuIssues').css('top', $('#menuIssues').position().top + 28);
                $('#contentMenuIssues').css('z-index', '500');
                $('#contentMenuIssues').css('padding', '4px');

                $('#contentMenuIssues').css('position', 'absolute');
                $('#contentMenuIssues').css('width', '200px');
                $('#contentMenuIssues').css('background-color', '#ffffff');
                $('#contentMenuIssues').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
                $('#contentMenuIssues').show();
            }
        });
    });

    $('#menuFilters').click(function (event) {
        event.preventDefault();
        event.stopPropagation();

        if ($('#contentMenuFilters').is(':visible') && $('#contentMenuFilters').html()) {
            $('#contentMenuFilters').hide();
            return
        }

        $('#menuFilters').css('background-color', '#cccccc');
        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'project') {
            $('#menuProjects').css('background-color', '#eeeeee');
        } else
            $('#menuProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'home') {
            $('#menuHome').css('background-color', '#eeeeee');
        } else
            $('#menuHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'svn') {
            $('#menuSVN').css('background-color', '#eeeeee');
        } else
            $('#menuSVN').css('background-color', '#6A8EB2');

        if (menuSelected == 'issue') {
            $('#menuIssues').css('background-color', '#eeeeee');
        } else
            $('#menuIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'agile') {
            $('#menuAgile').css('background-color', '#eeeeee');
        } else
            $('#menuAgile').css('background-color', '#6A8EB2');

        if (menuSelected == 'helpdesk') {
            $('#menuHelpDesk').css('background-color', '#eeeeee');
        } else
            $('#menuHelpDesk').css('background-color', '#6A8EB2');

        $('#menu_top_user').css('background-color', '#003466');

        $('#contentMenuProjects').hide();
        $('#contentMenuIssues').hide();
        $('#contentMenuHome').hide();
        $('#contentUserHome').hide();
        $('#contentMenuDocumentator').hide();
        $('#contentMenuSVN').hide();
        $('#contentMenuHelpDesk').hide();
        $('#menu_more_actions').hide();
        $('#menu_workflow').hide();
        $('#contentMenuAgile').hide();
        $('#menuIssueSearchViewOptionsContent').hide();
        $('#contentMenuUserSummaryFilters').hide();
        $('#menu_add_to_sprint').hide();

        $.ajax({
            type: "POST",
            url: '/menu/filters-menu',
            data: {
            },
            success: function (response) {
                $('#contentMenuFilters').html(response);
                $('#contentMenuFilters').css('left',$('#menuFilters').position().left);
                $('#contentMenuFilters').css('top', $('#menuFilters').position().top + 28);
                $('#contentMenuFilters').css('z-index', '500');
                $('#contentMenuFilters').css('padding', '4px');

                $('#contentMenuFilters').css('position', 'absolute');
                $('#contentMenuFilters').css('width', '200px');
                $('#contentMenuFilters').css('background-color', '#ffffff');
                $('#contentMenuFilters').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
                $('#contentMenuFilters').show();
            }
        });
    });

    $('#menuProjects').click(function (event) {
        event.preventDefault();
        event.stopPropagation();
        if ($('#contentMenuProjects').is(':visible') && $('#contentMenuProjects').html()) {
            $('#contentMenuProjects').hide();
            return
        }

        $('#menuProjects').css('background-color', '#cccccc');
        $('#contentMenuIssues').hide();
        $('#contentMenuHome').hide();
        $('#contentUserHome').hide();
        $('#contentMenuSVN').hide();
        $('#contentMenuFilters').hide();
        $('#contentMenuHelpDesk').hide();
        $('#contentMenuDocumentator').hide();
        $('#menu_more_actions').hide();
        $('#menu_workflow').hide();
        $('#contentMenuAgile').hide();
        $('#menuIssueSearchViewOptionsContent').hide();
        $('#contentMenuUserSummaryFilters').hide();
        $('#menu_add_to_sprint').hide();

        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'issue') {
            $('#menuIssues').css('background-color', '#eeeeee');
        } else
            $('#menuIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'home') {
            $('#menuHome').css('background-color', '#eeeeee');
        } else
            $('#menuHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'svn') {
            $('#menuSVN').css('background-color', '#eeeeee');
        } else
            $('#menuSVN').css('background-color', '#6A8EB2');

        if (menuSelected == 'filters') {
            $('#menuFilters').css('background-color', '#eeeeee');
        } else
            $('#menuFilters').css('background-color', '#6A8EB2');

        if (menuSelected == 'agile') {
            $('#menuAgile').css('background-color', '#eeeeee');
        } else
            $('#menuAgile').css('background-color', '#6A8EB2');

        if (menuSelected == 'helpdesk') {
            $('#menuHelpDesk').css('background-color', '#eeeeee');
        } else
            $('#menuHelpDesk').css('background-color', '#6A8EB2');

        $('#menu_top_user').css('background-color', '#003466');

        $.ajax({
            type: "POST",
            url: '/menu/projects-menu',
            success: function (response) {
                $('#contentMenuProjects').html(response);
                $('#contentMenuProjects').css('left', $('#menuProjects').position().left);
                $('#contentMenuProjects').css('top', $('#menuProjects').position().top + 28);
                $('#contentMenuProjects').css('z-index', '500');
                $('#contentMenuProjects').css('padding', '4px');
                $('#contentMenuProjects').css('position', 'absolute');
                $('#contentMenuProjects').css('width', '200px');
                $('#contentMenuProjects').css('background-color', '#ffffff');
                $('#contentMenuProjects').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
                $('#contentMenuProjects').show();
            }
        });
    });

    $('#menuHome').click(function (event) {
        event.preventDefault();
        event.stopPropagation();

        if ($('#contentMenuHome').is(':visible') && $('#contentMenuHome').html()) {
            $('#contentMenuHome').hide();
            return
        }

        $('#menuHome').css('background-color', '#cccccc');
        $('#contentMenuIssues').hide();
        $('#contentMenuProjects').hide();
        $('#contentUserHome').hide();
        $('#contentMenuSVN').hide();
        $('#contentMenuFilters').hide();
        $('#contentMenuDocumentator').hide();
        $('#contentMenuHelpDesk').hide();
        $('#menu_more_actions').hide();
        $('#menu_workflow').hide();
        $('#contentMenuAgile').hide();
        $('#menuIssueSearchViewOptionsContent').hide();
        $('#contentMenuUserSummaryFilters').hide();
        $('#menu_add_to_sprint').hide();

        var menuSelected = $('#menu_selected').val();

        if (menuSelected == 'project') {
            $('#menuProjects').css('background-color', '#eeeeee');
        } else
            $('#menuProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'issue') {
            $('#menuIssues').css('background-color', '#eeeeee');
        } else
            $('#menuIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'svn') {
            $('#menuSVN').css('background-color', '#eeeeee');
        } else
            $('#menuSVN').css('background-color', '#6A8EB2');

        if (menuSelected == 'filters') {
            $('#menuFilters').css('background-color', '#eeeeee');
        } else
            $('#menuFilters').css('background-color', '#6A8EB2');

        if (menuSelected == 'agile') {
            $('#menuAgile').css('background-color', '#eeeeee');
        } else
            $('#menuAgile').css('background-color', '#6A8EB2');

        if (menuSelected == 'helpdesk') {
            $('#menuHelpDesk').css('background-color', '#eeeeee');
        } else
            $('#menuHelpDesk').css('background-color', '#6A8EB2');

        $('#menu_top_user').css('background-color', '#003466');

        $.ajax({
            type: "POST",
            url: '/menu/home-menu',
            data: {
            },
            success: function (response) {
                $('#contentMenuHome').html(response);
                $('#contentMenuHome').css('left',$('#menuHome').position().left);
                $('#contentMenuHome').css('top', $('#menuHome').position().top + 28);
                $('#contentMenuHome').css('z-index', '500');
                $('#contentMenuHome').css('padding', '4px');

                $('#contentMenuHome').css('position', 'absolute');
                $('#contentMenuHome').css('width', '200px');
                $('#contentMenuHome').css('background-color', '#ffffff');
                $('#contentMenuHome').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
                $('#contentMenuHome').show();
            }
        });
    });

    $('#btnIssueWorkflow').click(function (event) {
        event.preventDefault();
        event.stopPropagation();
        if ($(this).hasClass('disabled'))
            return;

        $('#menu_more_actions').hide();
        if ($('#menu_workflow').is(':visible')) {
            $('#menu_workflow').hide();
        } else {
            $('#menu_workflow').css('left',$('#btnIssueWorkflow').position().left);
            $('#menu_workflow').css('top', $('#btnIssueWorkflow').position().top + 32);
            $('#menu_workflow').show();
        }

        $('#contentMenuIssues').hide();
        $('#contentMenuProjects').hide();
        $('#contentMenuSVN').hide();
        $('#contentMenuHome').hide();
        $('#contentMenuHelpDesk').hide();
        $('#contentUserHome').hide();
        $('#contentMenuAgile').hide();
        $('#contentMenuFilters').hide();
        $('#contentMenuDocumentator').hide();

        var menuSelected = $('#menu_selected').val();
        if (menuSelected == 'issue') {
            $('#menuIssues').css('background-color', '#eeeeee');
        } else
            $('#menuIssues').css('background-color', '#6A8EB2');

        if (menuSelected == 'project') {
            $('#menuProjects').css('background-color', '#eeeeee');
        } else
            $('#menuProjects').css('background-color', '#6A8EB2');

        if (menuSelected == 'home') {
            $('#menuHome').css('background-color', '#eeeeee');
        } else
            $('#menuHome').css('background-color', '#6A8EB2');

        if (menuSelected == 'svn') {
            $('#menuSVN').css('background-color', '#eeeeee');
        } else
            $('#menuSVN').css('background-color', '#6A8EB2');

        if (menuSelected == 'filters') {
            $('#menuFilters').css('background-color', '#eeeeee');
        } else
            $('#menuFilters').css('background-color', '#6A8EB2');

        if (menuSelected == 'agile') {
            $('#menuAgile').css('background-color', '#eeeeee');
        } else
            $('#menuAgile').css('background-color', '#6A8EB2');

        if (menuSelected == 'helpdesk') {
            $('#menuHelpDesk').css('background-color', '#eeeeee');
        } else
            $('#menuHelpDesk').css('background-color', '#6A8EB2');

        $('#menu_top_user').css('background-color', '#003466');
    });

    $("#report_agile_select_sprint").on('click', function (event) {
        event.preventDefault();
        $('#submenu_reports_available').hide();

        if ($(this).hasClass('disabled'))
            return;

        if ($('#submenu_completed_sprints').is(':visible')) {
            $('#submenu_completed_sprints').hide();
        } else {
            $('#submenu_completed_sprints').css('left',$('#report_agile_select_sprint').position().left);
            $('#submenu_completed_sprints').css('top', $('#report_agile_select_sprint').position().top + 32);
            $('#submenu_completed_sprints').show();
        }
    });

    $("#report_agile_select_report").on('click', function (event) {
        event.preventDefault();
        $('#submenu_completed_sprints').hide();

        if ($(this).hasClass('disabled'))
            return;

        if ($('#submenu_reports_available').is(':visible')) {
            $('#submenu_reports_available').hide();
        } else {
            $('#submenu_reports_available').css('left',$('#report_agile_select_report').position().left);
            $('#submenu_reports_available').css('top', $('#report_agile_select_report').position().top + 32);
            $('#submenu_reports_available').show();
        }
    });

    $("[id^='issue_search_']").click(function (event) {
        event.preventDefault();
        var issueId = $(this).attr("id").replace('issue_search_', '');

        $.ajax({
            type: "POST",
            url: '/yongo/issue/get-search-menu',
            data: {
                id: issueId
            },
            success: function (response) {

                $('#contentMenuIssueSearchOptions').html(response);
                $('#contentMenuIssueSearchOptions').css('left', $('#issue_search_' + issueId).position().left - 140 + 13);
                $('#contentMenuIssueSearchOptions').css('top', $('#issue_search_' + issueId).position().top + 24);
                $('#contentMenuIssueSearchOptions').css('z-index', '500');
                $('#contentMenuIssueSearchOptions').css('padding', '4px');

                $('#contentMenuIssueSearchOptions').css('position', 'absolute');
                $('#contentMenuIssueSearchOptions').css('width', '140px');
                $('#contentMenuIssueSearchOptions').css('background-color', '#ffffff');
                $('#contentMenuIssueSearchOptions').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
                $('#contentMenuIssueSearchOptions').show();
            }
        });

        if ($('#contentMenuIssueSearchOptions').is(':visible') && $('#contentMenuIssueSearchOptions').html()) {
            $('#contentMenuIssueSearchOptions').hide();
        }
    });
});