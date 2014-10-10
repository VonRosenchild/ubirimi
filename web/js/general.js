var selected_rows = [];
var PRODUCT_DOCUMENTADOR = 4;
var PRODUCT_QUICK_NOTES = 7;

function setInputFieldFocused(id) {

    var SearchInput = $('#' + id);
    SearchInput.val(SearchInput.val());
    var strLength = SearchInput.val().length;
    SearchInput.focus();
    SearchInput[0].setSelectionRange(strLength, strLength);
}

function addParameterToURL(url, parameter) {
    url += (url.split('?')[1] ? '&':'?') + parameter;

    return url;
}

function validateDate(stringDate) {

    var year = stringDate.split("-")[0];
    var month = stringDate.split("-")[1];
    var day = stringDate.split("-")[2];

    var dayobj = new Date(year, month - 1, day);
    if ((dayobj.getMonth() + 1 != month) || (dayobj.getDate() != day) || (dayobj.getFullYear() != year))
        return false;

    return true
}

// resize the create/edit modals as the users resizes the browser window
function resizeModals() {
    if ($(".ui-dialog-content").dialog("isOpen")) {
        var newHeight = ($(window).height() - $(".ui-dialog-content").height()) / 2 + $(".ui-dialog-content").height() - 180;
        if (newHeight < 200) {
            newHeight = 200;
        }

        $(".ui-dialog-content").css('max-height', newHeight);

        $(".ui-dialog").css('left', ($(window).width() - $(".ui-dialog").width()) / 2);
        $(".ui-dialog").css('top', ($(window).height() - $(".ui-dialog").height()) / 2);
    }
}

// close open menus when you click outside
$(document).mouseup(function (e) {
    var contentToClose = ['#contentMenuHome', '#contentMenuIssues', '#contentMenuProjects', '#contentMenuAgile',
        '#contentMenuMailGeneral', '#contentMenuUsersGeneral', '#contentUserHomeGeneral', '#menu_add_to_sprint',
        '#contentMenuSVN', '#contentMenuFilters', '#contentMenuAdminProjects', '#contentMenuAdminUsers', '#contentMenuAdminIssues', '#contentMenuAdminSystem',
        '#contentMenuUserMenu', '#contentMenuIssueSearchOptions', '#menu_child_pages', '#contentMenuDocumentator', '#menu_page_tools',
        '#contentMenuAdminDocSpaces', '#contentMenuAdminDocUsersSecurity', '#contentMenuCalendars', '#contentMenuHelpDesk', '#contentMenuNotebooks',
        '#contentAddIssueWatcher', '#content_chose_display_columns'];


    for (var i = 0; i < contentToClose.length; i++) {
        var container = $(contentToClose[i]);
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    }
});

function closeOpenMenus() {

    var menuSelected = $('#menu_selected').val();

    if (menuSelected == 'home') {
        $('#menuHome').css('background-color', '#eeeeee');
    } else
        $('#menuHome').css('background-color', '#6A8EB2');

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

    if (menuSelected == 'agile') {
        $('#menuAgile').css('background-color', '#eeeeee');
    } else
        $('#menuAgile').css('background-color', '#6A8EB2');

    if (menuSelected == 'help_desk') {
        $('#menuHelpDesk').css('background-color', '#eeeeee');
    } else
        $('#menuHelpDesk').css('background-color', '#6A8EB2');

    if (menuSelected == 'notebooks') {
        $('#menuNotebooks').css('background-color', '#eeeeee');
    } else
        $('#menuNotebooks').css('background-color', '#6A8EB2');

    if (menuSelected == 'issue') {
        $('#menuAdminIssues').css('background-color', '#eeeeee');
    } else
        $('#menuAdminIssues').css('background-color', '#6A8EB2');

    if (menuSelected == 'project') {
        $('#menuAdminProjects').css('background-color', '#eeeeee');
    } else
        $('#menuAdminProjects').css('background-color', '#6A8EB2');

    if (menuSelected == 'user') {
        $('#menuAdminUsers').css('background-color', '#eeeeee');
    } else
        $('#menuAdminUsers').css('background-color', '#6A8EB2');

    if (menuSelected == 'system') {
        $('#menuAdminSystem').css('background-color', '#eeeeee');
    } else
        $('#menuAdminSystem').css('background-color', '#6A8EB2');

    if (menuSelected == 'doc_spaces') {
        $('#menuAdminDocSpaces').css('background-color', '#eeeeee');
    } else
        $('#menuAdminDocSpaces').css('background-color', '#6A8EB2');

    if (menuSelected == 'doc_users') {
        $('#menuAdminDocUsers').css('background-color', '#eeeeee');
    } else
        $('#menuAdminDocUsers').css('background-color', '#6A8EB2');

    if (menuSelected == 'calendars') {
        $('#menuCalendars').css('background-color', '#eeeeee');
    } else
        $('#menuCalendars').css('background-color', '#6A8EB2');

    $('#menu_top_user').css('background-color', '#003466');
    $('#menu_top_userAdmin').css('background-color', '#003466');
    $('#menuUsersGeneral').css('background-color', '#6A8EB2');
    $('#menuDocumentator').css('background-color', '#eeeeee');

    var menuToClose = ['menu_more_actions', 'menu_workflow', 'contentUserHome', 'contentMenuUserSummaryFilters'];
    for (var i = 0; i < menuToClose.length; i++) {
        if ($('#' + menuToClose[i]).is(':visible')) {
            $('#' + menuToClose[i]).hide();
        }
    }
}

$('document').ready(function () {
    $(".select2Input").select2();
    $(".select2InputSmall").select2();
    $(".select2InputMedium").select2();
    $(".select2InputLarge").select2();

    $('.filter-date-regular').datepicker({
        dateFormat: "yy-mm-dd",
        selectOtherMonths: true
    });

    $('#ubirimi_quick_search, #calendar_quick_search').on('click', function (event) {
        $(this).val('');
    });
    $('#ubirimi_quick_search, #calendar_quick_search').focusout(function () {
        $(this).val('Quick Search');
    });

    var checks = $("[id^='el_check_']");

    for (var i = 0; i < checks.length; i++) {
        var id = checks[i].getAttribute('id');
        if ($('#' + id).prop("checked")) {
            selected_rows.push(id.replace('el_check_', ''));

            $('#table_row_' + id.replace('el_check_', '')).css('background-color', '#f5f5f5');
        }
    }

    updateButtonsState();

    $('#send_feedback').on('click', function (event) {

        event.preventDefault();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'We appreciate you feedback',
            buttons: [
                {
                    text: "Send Feedback",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/send-feedback',
                            data: {
                                like: $('#feedback_like').val(),
                                improve: $('#feedback_improved').val(),
                                new_features: $('#feedback_new_features').val(),
                                experience: $('#feedback_experience').val()
                            },
                            success: function (response) {
                                $("#modalSendFeedback").dialog('destroy');
                                $("#modalSendFeedback").empty();

                                $('#topMessageBox').html('Thanks for the feedback!');
                                $('#topMessageBox').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');

                                $('#topMessageBox').show();

                                $("#topMessageBox").fadeOut(4600, "linear");
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ],
            close: function () {
                $("#modalSendFeedback").dialog('destroy');
                $("#modalSendFeedback").empty();
            }
        };

        $("#modalSendFeedback").load("/send-feedback-dialog", [], function () {
            $("#modalSendFeedback").dialog(options);
            $("#modalSendFeedback").dialog("open");
            $('.select2Small').select2();
        });
    });

    $("#content_list tr").on('mouseenter mouseleave',
        function (event) {
            if (event.type == 'mouseenter') {

                $(this).css('background-color', '#f5f5f5');
                $(this).css('cursor', 'pointer');
            }

            if (event.type == 'mouseleave') {
                $(this).css('background-color', 'white');
            }
        }
    );

    function updateButtonsState(id) {
        // enable the buttons
        var classCSS = (selected_rows.length == 1) ? 'btn ubirimi-btn' : 'btn ubirimi-btn disabled';
        var classCSSDelete = '';//(selected_rows.length == 1) ? 'delete' : 'disabled';

        var addToSprintPossible = $('#add_to_sprint_possible').val();

        $('#btnEditIssue').attr('class', classCSS);
        $('#btnEditEvent').attr('class', classCSS);
        $('#btnDuplicate').attr('class', classCSS);
        $('#btnEditReleases').attr('class', classCSS);
        $('#btnEditComponent').attr('class', classCSS);
        $('#btnCreateSubcomponent').attr('class', classCSS);
        $('#btnEditRelease').attr('class', classCSS);
        $('#btnEditIssuePriority').attr('class', classCSS);
        $('#btnEditIssueStatus').attr('class', classCSS);
        $('#btnEditIssueType').attr('class', classCSS);
        $('#btnEditStepProperty').attr('class', classCSS);
        $('#btnEditPermRole').attr('class', classCSS);
        $('#btnEditSpace').attr('class', classCSS);
        $('#btnEditSpaceAdministration').attr('class', classCSS);
        $('#btnEditUserGroup').attr('class', classCSS);
        $('#btnEditGroupDocumentator').attr('class', classCSS);
        $('#btnEditLinkType').attr('class', classCSS);
        $('#btnAssignUsers').attr('class', classCSS);
        $('#btnAssignUsersToRole').attr('class', classCSS);
        $('#btnAssignGroupsToRole').attr('class', classCSS);
        $('#btnAssignUserInGroup').attr('class', classCSS);
        $('#btnEditUser').attr('class', classCSS);
        $('#btnEditCalendar').attr('class', classCSS);
        $('#btnEditNotebook').attr('class', classCSS);
        $('#btnEditTag').attr('class', classCSS);
        $('#btnCalendarSettings').attr('class', classCSS);
        $('#btnShareCalendar').attr('class', classCSS);
        $('#btnDeleteUser').attr('class', classCSS + ' ' + classCSSDelete);

        // svn
        $('#btnEditSvnRepo').attr('class', classCSS);
        $('#btnEditSvnUser').attr('class', classCSS);
        $('#btnDeleteSvnRepo').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteSvnAdministrator').attr('class', classCSS + ' ' + classCSSDelete);
        if ($('#svn_from_user_perspective').val() == 1) {
            $('#btnChangePasswordSvnUser').attr('class', 'btn ubirimi-btn');
            $('#btnSetPermissionsSvnUser').attr('class', 'btn ubirimi-btn');
        } else {
            $('#btnChangePasswordSvnUser').attr('class', classCSS);
            $('#btnSetPermissionsSvnUser').attr('class', classCSS);
        }
        $('#btnDeleteSvnUser').attr('class', classCSS + ' ' + classCSSDelete);

        // documentator
        $('#btnSpaceTools').attr('class', classCSS);
        $('#btnEditPage').attr('class', classCSS);
        $('#btnDeleteSpace').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeletePage').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnRestoreRevision').attr('class', classCSS);
        $('#btnRemoveRevision').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnPageRestore').attr('class', classCSS);
        $('#btnPagePurge').attr('class', classCSS);

        $('#btnEditIssueResolution').attr('class', classCSS);
        $('#btnEditWorkflowStep').attr('class', classCSS);
        $('#btnNewTransitionForStep').attr('class', classCSS);
        $('#btnDeleteIssuePriority').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteIssueStatus').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteIssueResolution').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteIssueType').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteProjectComponent').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteRelease').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeletePermRole').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteIssue').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteWorkflow').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteUserGroup').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteGroupDocumentator').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteEvent').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteStepProperty').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteWorkflowStep').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteBoard').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteOrganization').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteClientProject').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteClient').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteCustomField').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteScreenField').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteScreen').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnEditClientProject').attr('class', classCSS);
        $('#btnDeleteCalendar').attr('class', classCSS);
        $('#btnDeleteNotebook').attr('class', classCSS);
        $('#btnDeleteTag').attr('class', classCSS);
        $('#btnEditIssueSecurityScheme').attr('class', classCSS);
        $('#btnEditIssueSecuritySchemeLevel').attr('class', classCSS);
        $('#btnIssueSecurityLevels').attr('class', classCSS);
        $('#btnDeleteCustomFieldValue').attr('class', classCSS);
        $('#btnEditCustomFieldValue').attr('class', classCSS);

        $('#btnEditCustomField').attr('class', classCSS);
        $('#btnAssignUserToGroup, #btnAssignUserToGroupDocumentator').attr('class', classCSS);
        $('#btnEditFilter').attr('class', classCSS);
        $('#btnCustomFieldPlaceOnScreens').attr('class', classCSS);
        $('#btnDeleteFilter').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnEditWorkflow').attr('class', classCSS);
        $('#btnEditWorkflowScheme').attr('class', classCSS);
        $('#btnCopyWorkflowScheme').attr('class', classCSS);
        $('#btnCustomizefields').attr('class', classCSS);
        $('#btnEditScreen').attr('class', classCSS);
        $('#btnCopyScreen').attr('class', classCSS);
        $('#btnEditScreenMetaData').attr('class', classCSS);
        $('#btnEditBoard').attr('class', classCSS);
        $('#btnConfigureBoard').attr('class', classCSS);
        $('#btnEditIssueTypeScheme').attr('class', classCSS);
        $('#btnCopyIssueTypeScheme').attr('class', classCSS);
        $('#btnCopyWorkflowIssueTypeScheme').attr('class', classCSS);
        $('#btnEditFieldConfiguration').attr('class', classCSS);
        $('#btnEditScreenSchemeMetadata').attr('class', classCSS);
        $('#btnWorkflowViewAsText').attr('class', classCSS);
        $('#btnEditFieldConfigurationMetadata').attr('class', classCSS);
        $('#btnEditFieldConfigurationSchemeMetadata').attr('class', classCSS);
        $('#btnCopyFieldConfigurationScheme').attr('class', classCSS);
        $('#btnEditFieldConfigurationScheme').attr('class', classCSS);
        $('#btnEditScreenSchemeData').attr('class', classCSS);
        $('#btnEditIssueTypeScreenSchemeData').attr('class', classCSS);
        $('#btnEditFieldConfigurationSchemeData').attr('class', classCSS);
        $('#btnEditIssueTypeScreenScheme').attr('class', classCSS);
        $('#btnEditPermissionScheme').attr('class', classCSS);
        $('#btnCopyPermissionScheme').attr('class', classCSS);
        $('#btnCopyFieldConfiguration').attr('class', classCSS);
        $('#btnEditNotificationScheme').attr('class', classCSS);
        $('#btnEditSLACalendar').attr('class', classCSS);
        $('#btnDeleteSLACalendar').attr('class', classCSS);
        $('#btnCopyNotificationScheme').attr('class', classCSS);
        $('#btnEditProjectCategory').attr('class', classCSS);
        $('#btnEditOrganization').attr('class', classCSS);
        $('#btnEditCustomer').attr('class', classCSS);
        $('#btnEditPostFunction').attr('class', classCSS);
        $('#btnConfigureScreenScheme').attr('class', classCSS);
        $('#btnCopyScreenScheme').attr('class', classCSS);
        $('#btnCopyWorkflow').attr('class', classCSS);
        $('#btnFieldConfigurationScreen').attr('class', classCSS);
        $('#btnConfigureIssueTypeScreenScheme').attr('class', classCSS);
        $('#btnCopyIssueTypeScreenScheme').attr('class', classCSS);
        $('#btnDesignWorkflow').attr('class', classCSS);
        $('#btnPermissions').attr('class', classCSS);
        $('#btnAssignUserInGroupDocumentator').attr('class', classCSS);
        $('#btnDeleteFilterSubscription').attr('class', classCSS);

        if ($("[id^='el_check_']:checked").length) {
            if (parseInt(addToSprintPossible)) {
                $('#btnAddToSprint').removeClass('disabled');
            } else {
                $('#btnAddToSprint').addClass('disabled');
            }
        } else {
            $('#btnAddToSprint').addClass('disabled');
        }

        // check if there is a backlog value for the checkbox
        var backlogEnabled = true;
        if ($("[id^='el_check_']:checked").length) {
            $("[id^='el_check_']:checked").each(function() {
                var id = this.id.replace('el_check_', '');
                var alreadyInBacklog = parseInt($('#backlog_el_check_' + id).val());
                if (alreadyInBacklog) {
                    backlogEnabled = false;
                }
            });
        } else {
            backlogEnabled = false;
        }

        if (backlogEnabled) {
            $('#btnMoveToBacklog').attr('class', 'btn ubirimi-btn');
        } else {
            $('#btnMoveToBacklog').attr('class', 'btn ubirimi-btn disabled');
        }

        $('#btnNotifications').attr('class', classCSS);
        $('#btnUserAssignInProjectRole').attr('class', classCSS);
        $('#btnManageUsersInProjectRole').attr('class', classCSS);
        $('#btnManageGroupsInProjectRole').attr('class', classCSS);
        $('#btnDeleteIssueTypeScheme').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteProjectCategory').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteIssueTypeScreenScheme').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteWorkflowScheme').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeletePermissionScheme').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteNotificationScheme').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeletePostFunction').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteTransitions').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteFieldConfiguration').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteFieldConfigurationScheme').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteScreenScheme').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteWorkflowIssueTypeScheme').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteIssueSecurityScheme').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteIssueSecuritySchemeLevel').attr('class', classCSS + ' ' + classCSSDelete);
        $('#btnDeleteLinkType').attr('class', classCSS + ' ' + classCSSDelete);
    }

    $(document).on('click', '.table tr', function (event) {

        if (!$(this).attr('id')) {
            return;
        }

        var id = $(this).attr('id').replace('table_row_', '');

        if (!$("#el_check_" + id).length) {
            return;
        }

        if ($('#contextList').val() == 'agile') {
            var checked = $("#el_check_" + id).attr('checked');
            if (checked == 'checked')
                loadIssueAgileContext(null);
            else
                loadIssueAgileContext(id);
        }

        if ($('#el_check_' + id).is(':disabled')) {
            return
        }

        var current_selected_id = id;
        var found_index = selected_rows.indexOf(current_selected_id, 0);

        if (found_index == -1) {
            selected_rows.push(id);

            $("#el_check_" + id).prop('checked', 'checked');
            $(this).css('background-color', '#f5f5f5');
        } else {
            selected_rows.splice(found_index, 1);
            $("#el_check_" + id).attr('checked', null);
            $(this).css('background-color', 'white');
        }

        updateButtonsState(id);

        $(this).css('cursor', 'pointer');
        $('#btnEditUser').click(function (event) {
            event.preventDefault();
            if (selected_rows.length == 1)
                document.location.href = '/general-settings/users/edit/' + selected_rows[0];
        });
    });

    $('#continent_time_zone').change(function () {
        var continent = $('#continent_time_zone').val();
        $.ajax({
            type: "POST",
            url: '/general-settings/refresh-timezone',
            data: {
                zone: continent
            },
            success: function (response) {
                $('#general_settings_zone option').remove();
                $('#general_settings_zone').append(response);
            }
        });
    });

    $(document).on('click', '#user_join_group, #assign_user_btn, #assign_user_group_btn, #assign_perm_role_btn, #assign_project_btn, #assign_group_user_btn, #assign_group_btn', function (event) {
        event.preventDefault();
        var left_side, right_side, btn_left, btn_right;
        if ($(this).attr('id') == 'assign_user_btn') {
            left_side = 'all_users';
            right_side = 'assigned_users';
            btn_left = 'assign_user_btn';
            btn_right = 'remove_user_btn';
        } else if ($(this).attr('id') == 'assign_user_group_btn') {
            left_side = 'all_user_group';
            right_side = 'assigned_user_groups';
            btn_left = 'assign_user_group_btn';
            btn_right = 'remove_user_group_btn';
        } else if ($(this).attr('id') == 'assign_perm_role_btn') {
            left_side = 'all_perm_roles';
            right_side = 'assigned_perm_roles';
            btn_left = 'assign_perm_role_btn';
            btn_right = 'remove_perm_role_btn';
        } else if ($(this).attr('id') == 'assign_project_btn') {
            left_side = 'all_projects';
            right_side = 'assigned_projects';
            btn_left = 'assign_project_btn';
            btn_right = 'remove_project_btn';
        } else if ($(this).attr('id') == 'assign_group_user_btn') {
            left_side = 'all_groups';
            right_side = 'assigned_groups';
            btn_left = 'assign_group_user_btn';
            btn_right = 'remove_group_user_btn';
        } else if ($(this).attr('id') == 'assign_group_btn') {
            left_side = 'all_groups';
            right_side = 'assigned_groups';
            btn_left = 'assign_group_btn';
            btn_right = 'remove_group_btn';
        } else if ($(this).attr('id') == 'user_join_group') {
            left_side = 'available_groups';
            right_side = 'user_groups';
            btn_left = 'user_join_group';
            btn_right = 'user_leave_group';
        }

        if (!$('#' + btn_left).hasClass('disabled') && $('#' + left_side + ' option:selected').val()) {
            $('#' + right_side)
                .append($('<option>', { value: $('#' + left_side + ' option:selected').val() })
                    .text($('#' + left_side + ' option:selected').text()));
            $("#" + left_side + " option:selected").remove();

            if ($('#' + left_side + ' option').length == 0) {
                $('#' + btn_left).attr("class", "btn ubirimi-btn disabled");
                $('#' + btn_right).attr("class", "btn ubirimi-btn");
            } else {
                $('#' + btn_right).attr("class", "btn ubirimi-btn");
                $('#' + left_side + ' > option:nth-child(1)').attr('selected', true);
            }
        }
    });

    $(document).on('click', '#user_leave_group, #remove_user_btn, #remove_user_group_btn, #remove_perm_role_btn, #remove_project_btn, #remove_group_user_btn, #remove_group_btn', function (event) {
        event.preventDefault();
        var left_side, right_side, btn_left, btn_right;
        if ($(this).attr('id') == 'remove_user_btn') {
            left_side = 'all_users';
            right_side = 'assigned_users';
            btn_left = 'assign_user_btn';
            btn_right = 'remove_user_btn';
        } else if ($(this).attr('id') == 'remove_user_group_btn') {
            left_side = 'all_user_group';
            right_side = 'assigned_user_groups';
            btn_left = 'assign_user_group_btn';
            btn_right = 'remove_user_group_btn';
        } else if ($(this).attr('id') == 'remove_perm_role_btn') {
            left_side = 'all_perm_roles';
            right_side = 'assigned_perm_roles';
            btn_left = 'assign_perm_role_btn';
            btn_right = 'remove_perm_role_btn';
        } else if ($(this).attr('id') == 'remove_project_btn') {
            left_side = 'all_projects';
            right_side = 'assigned_projects';
            btn_left = 'assign_project_btn';
            btn_right = 'remove_project_btn';
        } else if ($(this).attr('id') == 'remove_group_user_btn') {
            left_side = 'all_groups';
            right_side = 'assigned_groups';
            btn_left = 'assign_group_user_btn';
            btn_right = 'remove_group_user_btn';
        } else if ($(this).attr('id') == 'remove_group_btn') {
            left_side = 'all_groups';
            right_side = 'assigned_groups';
            btn_left = 'assign_group_btn';
            btn_right = 'remove_group_btn';
        } else if ($(this).attr('id') == 'user_leave_group') {
            left_side = 'available_groups';
            right_side = 'user_groups';
            btn_left = 'user_join_group';
            btn_right = 'user_leave_group';
        }
        if (!$('#' + btn_right).hasClass('disabled') && $('#' + right_side + ' option:selected').val()) {
            $('#' + left_side)
                .append($('<option>', { value: $('#' + right_side + ' option:selected').val() })
                    .text($('#' + right_side + ' option:selected').text()));
            $("#" + right_side + " option:selected").remove();

            if ($('#' + right_side + ' option').length == 0) {
                $('#' + btn_right).attr("class", "btn ubirimi-btn disabled");
                $('#' + btn_left).attr("class", "btn ubirimi-btn");
            } else {
                $('#' + btn_left).attr("class", "btn ubirimi-btn");
                $('#' + right_side + ' > option:nth-child(1)').attr('selected', true);
            }
        }
    });

    $('html').click(function() {
        closeOpenMenus();

        return
    });

    $('#menu_top_user').click(function (event) {

        event.preventDefault();
        event.stopPropagation();

        if ($('#contentUserHome').is(':visible') && $('#contentUserHome').html()) {
            $('#contentUserHome').hide();

            return
        }
        $('#menu_top_user').css('background-color', '#808080');

        $('#contentMenuIssues').hide();
        $('#contentMenuProjects').hide();
        $('#contentMenuFilters').hide();
        $('#contentMenuHome').hide();
        $('#contentMenuAgile').hide();
        $('#contentMenuHelpDesk').hide();
        $('#contentMenuNotebooks').hide();
        $('#menuIssueSearchViewOptionsContent').hide();
        $('#contentMenuUserSummaryFilters').hide();
        $('#contentMenuMailGeneral').hide();
        $('#contentMenuUsersGeneral').hide();
        $('#contentMenuCalendars').hide();

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

        if (menuSelected == 'agile') {
            $('#menuAgile').css('background-color', '#eeeeee');
        } else
            $('#menuAgile').css('background-color', '#6A8EB2');

        if (menuSelected == 'helpdesk') {
            $('#menuHelpDesk').css('background-color', '#eeeeee');
        } else
            $('#menuHelpDesk').css('background-color', '#6A8EB2');

        if (menuSelected == 'notebooks') {
            $('#menuNotebooks').css('background-color', '#eeeeee');
        } else
            $('#menuNotebooks').css('background-color', '#6A8EB2');

        if (menuSelected == 'general_user') {
            $('#menuUsersGeneral').css('background-color', '#eeeeee');
        } else
            $('#menuUsersGeneral').css('background-color', '#6A8EB2');

        if (menuSelected == 'general_mail') {
            $('#menuMailGeneral').css('background-color', '#eeeeee');
        } else
            $('#menuMailGeneral').css('background-color', '#6A8EB2');

        if (menuSelected == 'calendars') {
            $('#menuCalendars').css('background-color', '#eeeeee');
        } else
            $('#menuCalendars').css('background-color', '#6A8EB2');

        $('#menuDocumentator').css('background-color', '#eeeeee');

        var hasAdminMenu = parseInt($('#has_administration_perm').val());

        var leftOffset = 0;
        leftOffset = (140 - $('#menu_top_user').width()) + 7;

        $('#contentUserHome').css('left', $('#menu_top_user').position().left - leftOffset);
        $('#contentUserHome').css('right', $('#menu_top_user').position().right);
        $('#contentUserHome').css('top', $('#menu_top_user').position().top + 44);
        $('#contentUserHome').css('z-index', '500');
        $('#contentUserHome').css('padding', '4px');

        $('#contentUserHome').css('position', 'absolute');
        $('#contentUserHome').css('width', '140px');
        $('#contentUserHome').css('background-color', '#ffffff');
        $('#contentUserHome').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentUserHome').show();
    });

    $('#menu_top_userHelpdesk').click(function (event) {
        event.preventDefault();
        event.stopPropagation();

        if ($('#contentUserHome').is(':visible') && $('#contentUserHome').html()) {
            $('#contentUserHome').hide();

            return
        }
        $('#menu_top_userHelpdesk').css('background-color', '#808080');

        $('#contentUserHome').css('left', $('#menu_top_userHelpdesk').position().left);
        $('#contentUserHome').css('right', $('#menu_top_userHelpdesk').position().right);
        $('#contentUserHome').css('top', $('#menu_top_userHelpdesk').position().top);
        $('#contentUserHome').css('z-index', '500');
        $('#contentUserHome').css('padding', '4px');

        $('#contentUserHome').css('position', 'absolute');
        $('#contentUserHome').css('width', '140px');
        $('#contentUserHome').css('background-color', '#ffffff');
        $('#contentUserHome').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentUserHome').show();
    });

    if (typeof $(".fancybox").fancybox == 'function') {
        $(".fancybox").fancybox({
            overlayColor : '#060',
            overlayOpacity : .3,
            transitionIn: 'elastic',
            transitionOut: 'elastic',
            easingIn: 'easeInSine',
            easingOut: 'easeOutSine',
            titlePosition: 'outside' ,
            cyclic: true
        });
    }

    if ($('#fileupload').fileupload) {
        $('#fileupload').fileupload({
            url: '/yongo/user/upload-profile-picture',

            singleFileUploads: true,
            start: function() {
                $('#loading').show();
                $('#profile-picture').hide();
            },
            done: function (e, data) {
                if (data.result == 'error') {
                    alert('Something went wrong! Please try again.');
                    $('#loading').hide();
                    $('#profile-picture').show();
                } else {
                    $('#profile-picture').attr('src', '/assets/users/' + data.result);
                    $('#loading').hide();
                    $('#profile-picture').show();
                }
            }
        });
    }
});