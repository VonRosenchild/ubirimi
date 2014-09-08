$('document').ready(function () {
    $('#addAgileColumn').click(function (event) {
        event.preventDefault();

        var boardId = $('#board_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Add Column',
            buttons: [
                {
                    text: "Add",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/agile/board-add-column',
                            data: {
                                board_id: boardId,
                                name: $('#agile_column_name').val(),
                                description: $('#agile_column_description').val()
                            },
                            success: function (response) {
                                $("#modalAddAgileColumn").dialog('destroy');
                                $("#modalAddAgileColumn").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalAddAgileColumn").dialog('destroy');
                        $("#modalAddAgileColumn").empty();
                    }
                }
            ],
            close: function () {
                $("#modalAddAgileColumn").dialog('destroy');
                $("#modalAddAgileColumn").empty();
            }
        };

        $("#modalAddAgileColumn").load("/agile/board-add-column-dialog", [], function () {
            $("#modalAddAgileColumn").dialog(options);
            $("#modalAddAgileColumn").dialog("open");
        });
    });

    $("[id^='delete_sprint_']").on('click', function (event) {
        event.preventDefault();

        var sprintId = $(this).attr("id").replace('delete_sprint_', '');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Sprint',
            buttons: [
                {
                    text: "Delete Sprint",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/agile/delete-sprint',
                            data: {
                                id: sprintId
                            },
                            success: function (response) {
                                $("#modalDeletePlannedSprint").dialog('destroy');
                                $("#modalDeletePlannedSprint").empty();

                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeletePlannedSprint").dialog('destroy');
                        $("#modalDeletePlannedSprint").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeletePlannedSprint").dialog('destroy');
                $("#modalDeletePlannedSprint").empty();
            }
        };

        $("#modalDeletePlannedSprint").load("/agile/delete-sprint-dialog", [], function () {
            $("#modalDeletePlannedSprint").dialog(options);
            $("#modalDeletePlannedSprint").dialog("open");
        });
    });

    $("[id^='deleteAgileColumn_']").on('mouseenter', function (event) {
        $(this).css('cursor', 'pointer');
    });

    $("[id^='deleteAgileColumn_']").on('click', function (event) {
        event.preventDefault();

        var columnId = $(this).attr("id").replace('deleteAgileColumn_', '');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Column Confirmation',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/agile/board-delete-column',
                            data: {
                                id: columnId
                            },
                            success: function (response) {
                                $("#modalDeleteAgileColumn").dialog('destroy');
                                $("#modalDeleteAgileColumn").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteAgileColumn").dialog('destroy');
                        $("#modalDeleteAgileColumn").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteAgileColumn").dialog('destroy');
                $("#modalDeleteAgileColumn").empty();
            }
        };

        $("#modalDeleteAgileColumn").load("/agile/board-delete-column-dialog", [], function () {
            $("#modalDeleteAgileColumn").dialog(options);
            $("#modalDeleteAgileColumn").dialog("open");
        });
    });

    $("#btnAddSprint").on('click', function (event) {
        event.preventDefault();

        var boardId = $('#board_id').val();
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Add Sprint',
            buttons: [
                {
                    text: "Add",
                    click: function () {

                        var sprintName = $.trim($('#sprint_name').val());
                        if (sprintName == '') {
                            $('#empty_sprint_name').html('The name of the sprint can not be empty.');
                            return
                        }

                        $.ajax({
                            type: "POST",
                            url: '/agile/add-sprint',
                            data: {
                                name: sprintName,
                                board_id: boardId
                            },
                            success: function (response) {
                                $("#modalAddSprint").dialog('destroy');
                                $("#modalAddSprint").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalAddSprint").dialog('destroy');
                        $("#modalAddSprint").empty();
                    }
                }
            ],
            close: function () {
                $("#modalAddSprint").dialog('destroy');
                $("#modalAddSprint").empty();
            }
        };

        $("#modalAddSprint").load("/agile/add-sprint-dialog/" + boardId, [], function () {
            $("#modalAddSprint").dialog(options);
            $("#modalAddSprint").dialog("open");
        });
    });

    $("[id^='start_sprint_']").on('click', function (event) {
        event.preventDefault();

        var sprintId = $(this).attr("id").replace('start_sprint_', '');
        var boardId = $('#board_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Start Sprint',
            buttons: [
                {
                    text: "Start",
                    click: function () {
                        var startDate = $('#sprint_start_date').val();
                        var endDate = $('#sprint_end_date').val();

                        var canContinue = true;

                        if (!validateDate(startDate)) {
                            $('#invalid_start_sprint_date').html('Invalid date');
                            canContinue = false;
                        }
                        if (!validateDate(endDate)) {
                            $('#invalid_end_sprint_date').html('Invalid date');
                            canContinue = false;
                        }

                        if (!canContinue)
                            return;

                        $.ajax({
                            type: "POST",
                            url: '/agile/start-sprint',
                            data: {
                                id: sprintId,
                                start_date: $('#sprint_start_date').val(),
                                end_date: $('#sprint_end_date').val(),
                                name: $('#sprint_name').val()
                            },
                            success: function (response) {
                                $("#modalSprintStart").dialog('destroy');
                                $("#modalSprintStart").empty();

                                location.href = '/agile/board/work/' + sprintId + '/' + boardId;
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalSprintStart").dialog('destroy');
                        $("#modalSprintStart").empty();
                    }
                }
            ],
            close: function () {
                $("#modalSprintStart").dialog('destroy');
                $("#modalSprintStart").empty();
            }
        };

        $("#modalSprintStart").load("/agile/start-sprint-dialog/" + sprintId, [], function () {
            $("#modalSprintStart").dialog(options);
            $("#modalSprintStart").dialog("open");

            $("#sprint_start_date").datepicker({
                dateFormat: "yy-mm-dd"
            });
            $("#sprint_end_date").datepicker({dateFormat: "yy-mm-dd"});
        });
    });

    $('#btnDeleteBoard').on('click', function (event) {
        event.preventDefault();

        if (!selected_rows.length)
            return;

        var boardId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Agile Board',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/agile/delete-board',
                            data: {
                                id: boardId
                            },
                            success: function (response) {
                                $("#modalDeleteBoard").dialog('destroy');
                                $("#modalDeleteBoard").empty();
                                location.reload();
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
                $("#modalDeleteBoard").dialog('destroy');
                $("#modalDeleteBoard").empty();
            }
        };

        $("#modalDeleteBoard").load("/agile/delete-board-dialog", [], function () {
            $("#modalDeleteBoard").dialog(options);
            $("#modalDeleteBoard").dialog("open");
        });
    });

    $("#submenu_close_sprint").on('click', function (event) {
        event.preventDefault();
        var sprintId = $('#sprint_id').val();
        var boardId = $('#board_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Complete Sprint',
            buttons: [
                {
                    text: 'Complete',
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/agile/complete-sprint',
                            data: {
                                id: sprintId,
                                board_id: boardId
                            },
                            success: function (response) {
                                $("#agileCompleteSprint").dialog('destroy');
                                $("#agileCompleteSprint").empty();
                                location.href = '/agile/board/report/' + sprintId + '/' + boardId + '/sprint_report';
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#agileCompleteSprint").dialog('close');
                    }
                }
            ],
            close: function () {
                $("#agileCompleteSprint").dialog('close');
            }
        };

        $("#agileCompleteSprint").load("/agile/complete-sprint-confirm/" + sprintId + '/' + boardId, [], function () {
            $("#agileCompleteSprint").dialog(options);
            $("#agileCompleteSprint").dialog("open");
        });
    });
    $(document).on('click', "[id^='agile_plan_assign_other_']", function (event) {

        event.preventDefault();

        var issueId = $(this).attr("id").replace('agile_plan_assign_other_', '').split('_')[0];
        var projectId = $(this).attr("id").replace('agile_plan_assign_other_', '').split('_')[1];
        var userAssignedId = $(this).attr("id").replace('agile_plan_assign_other_', '').split('_')[2];
        var refreshPage = $(this).attr("id").replace('agile_plan_assign_other_', '').split('_')[3];

        var showCloseButton = 0;
        if ($('#content_agile_issue').length) {
            showCloseButton = 1;
        }

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Assign Issue',
            buttons: [
                {
                    text: "Assign",
                    click: function () {

                        if (userAssignedId == $('#render_assign_issue_field_type_assignee').val()) {
                            $('#issue_new_ua_id_error').html('The issue is already assigned to this user.');
                            return
                        }
                        $.ajax({
                            type: "POST",
                            url: '/yongo/issue/assign',
                            data: {
                                issue_id: issueId,
                                user_assigned_id: $('#render_assign_issue_field_type_assignee').val(),
                                comment: $('#render_assign_issue_field_type_comment').val()
                            },
                            success: function (response) {
                                if (refreshPage) {
                                    window.location.reload();
                                } else {
                                    $.ajax({
                                        type: "POST",
                                        url: '/agile/render-issue',
                                        data: {
                                            id: issueId,
                                            close: showCloseButton
                                        },
                                        success: function (response) {

                                            if ($('#agileIssueContent').length) {
                                                $('#agileIssueContent').html(response);
                                            } else if ($('#content_agile_issue').length) {
                                                $('#content_agile_issue > div').html(response);
                                            }
                                        }
                                    });
                                    $("#modalEditIssueAssign").dialog('destroy');
                                    $("#modalEditIssueAssign").empty();
                                }
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
                $("#modalEditIssueAssign").dialog('destroy');
                $("#modalEditIssueAssign").empty();
            }
        };

        $("#modalEditIssueAssign").load("/yongo/issue/assign-dialog/" + issueId + '/' + projectId, [], function () {
            $("#modalEditIssueAssign").dialog(options);
            $("#modalEditIssueAssign").dialog("open");
        });
    });
});