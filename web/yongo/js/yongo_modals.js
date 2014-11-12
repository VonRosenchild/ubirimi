$('document').ready(function () {

    $(".ubirimiModalDialog" ).on("dialogopen", function(event, ui) {
        resizeModals();
    });

    $('#btnDeleteIssueStatus').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var deletePossible = $('#delete_possible_' + selected_rows[0]).val();

        if (deletePossible == 0) {
            var options = {
                dialogClass: "ubirimi-dialog",
                title: 'Delete Issue Status Not Possible',
                buttons: [
                    {
                        text: "Close",
                        click: function () {
                            $(this).dialog("close");
                        }
                    }
                ],
                close: function () {
                    $("#deleteIssueStatus").dialog('destroy');
                    $("#deleteIssueStatus").empty();
                }
            };

            $("#deleteIssueStatus").load("/yongo/administration/issue/delete-status-not-possible", [], function () {
                $("#deleteIssueStatus").dialog(options);
                $("#deleteIssueStatus").dialog("open");
            });
        } else {
            var options = {
                dialogClass: "ubirimi-dialog",
                title: 'Delete Issue Status Confirmation',
                buttons: [
                    {
                        text: "Delete",
                        click: function () {
                            $.ajax({
                                type: "POST",
                                url: '/yongo/administration/issue/delete-status',
                                data: {
                                    id: selected_rows[0]
                                },
                                success: function (response) {
                                    $("#deleteIssueStatus").dialog('destroy');
                                    $("#deleteIssueStatus").empty();
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
                    $("#deleteIssueStatus").dialog('destroy');
                    $("#deleteIssueStatus").empty();
                }
            };

            $("#deleteIssueStatus").load("/yongo/administration/issue/delete-status-confirm/" + selected_rows[0], [], function () {
                $("#deleteIssueStatus").dialog(options);
                $("#deleteIssueStatus").dialog("open");
            });
        }
    });

    $('#btnDeleteLinkType').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var linkTypeId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Link Type',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/issue/delete-link-type',
                            data: {
                                id: linkTypeId,
                                action: $('[name="action_remove_link"]').val(),
                                new_id: $('#new_link_type').val()
                            },
                            success: function (response) {
                                $("#modalDeleteLinkType").dialog('destroy');
                                $("#modalDeleteLinkType").empty();

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
                $("#modalDeleteLinkType").dialog('destroy');
                $("#modalDeleteLinkType").empty();
            }
        };

        $("#modalDeleteLinkType").load("/yongo/administration/issue/delete-link-type-confirm/" + linkTypeId, [], function () {
            $("#modalDeleteLinkType").dialog(options);
            $("#modalDeleteLinkType").dialog("open");
        });
    });

    $('#btnDeleteCustomFieldValue').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var customFieldValueId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Custom Field Value',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/custom-field/value/delete',
                            data: {
                                id: customFieldValueId
                            },
                            success: function (response) {
                                $("#modalDeleteCustomFieldValue").dialog('destroy');
                                $("#modalDeleteCustomFieldValue").empty();

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
                $("#modalDeleteLinkType").dialog('destroy');
                $("#modalDeleteLinkType").empty();
            }
        };

        $("#modalDeleteCustomFieldValue").load("/yongo/administration/custom-field/value/dialog/delete/" + customFieldValueId, [], function () {
            $("#modalDeleteCustomFieldValue").dialog(options);
            $("#modalDeleteCustomFieldValue").dialog("open");
        });
    });

    $('#btnDeleteIssueSecurityScheme').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var issueSecuritySchemeId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Issue Security Scheme',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/issue/delete-issue-security-scheme',
                            data: {
                                id: issueSecuritySchemeId
                            },
                            success: function (response) {
                                $("#modalDeleteIssueSecurityScheme").dialog('destroy');
                                $("#modalDeleteIssueSecurityScheme").empty();

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
                $("#modalDeleteIssueSecurityScheme").dialog('destroy');
                $("#modalDeleteIssueSecurityScheme").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteIssueSecurityScheme").load("/yongo/administration/issue/delete-issue-security-scheme-confirm/" + issueSecuritySchemeId + '/' + delete_possible, [], function () {
            $("#modalDeleteIssueSecurityScheme").dialog(options);
            $("#modalDeleteIssueSecurityScheme").dialog("open");
        });
    });

    $('#btnDeleteEvent').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var eventId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Event',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/event/delete',
                            data: {
                                id: eventId
                            },
                            success: function (response) {
                                $("#modalDeleteEvent").dialog('destroy');
                                $("#modalDeleteEvent").empty();

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
                $("#modalDeleteIssueSecurityScheme").dialog('destroy');
                $("#modalDeleteIssueSecurityScheme").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteEvent").load("/yongo/administration/event/delete-confirm/" + eventId + '/' + delete_possible, [], function () {
            $("#modalDeleteEvent").dialog(options);
            $("#modalDeleteEvent").dialog("open");
        });
    });

    $('#btnDeleteIssueSecuritySchemeLevel').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var issueSecuritySchemeLevelId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Issue Security Scheme Level',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/issue/delete-issue-security-scheme-level',
                            data: {
                                id: issueSecuritySchemeLevelId,
                                new_level_id: $('#new_security_level').val()
                            },
                            success: function (response) {
                                $("#modalDeleteIssueSecuritySchemeLevel").dialog('destroy');
                                $("#modalDeleteIssueSecuritySchemeLevel").empty();

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
                $("#modalDeleteIssueSecuritySchemeLevel").dialog('destroy');
                $("#modalDeleteIssueSecuritySchemeLevel").empty();
            }
        };

        $("#modalDeleteIssueSecuritySchemeLevel").load("/yongo/administration/issue/delete-issue-security-scheme-level-confirm/" + issueSecuritySchemeLevelId, [], function () {
            $("#modalDeleteIssueSecuritySchemeLevel").dialog(options);
            $("#modalDeleteIssueSecuritySchemeLevel").dialog("open");
        });
    });

    $('#btnDeleteAllConditions').on('click', function (event) {
        event.preventDefault();

        var transitionId = $('#transition_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete All Conditions',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/workflow/all-condition-delete',
                            data: {
                                id: transitionId
                            },
                            success: function (response) {
                                $("#modalDeleteAllConditions").dialog('destroy');
                                $("#modalDeleteAllConditions").empty();

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
                $("#modalDeleteAllConditions").dialog('destroy');
                $("#modalDeleteAllConditions").empty();
            }
        };

        $("#modalDeleteAllConditions").load("/yongo/administration/workflow/all-condition-delete-confirm/" + transitionId, [], function () {
            $("#modalDeleteAllConditions").dialog(options);
            $("#modalDeleteAllConditions").dialog("open");
        });
    });

    $('#btnDeleteProjectCategory').on('click', function (event) {
        event.preventDefault();
        if (selected_rows.length != 1) {
            return
        }
        var categoryId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Project Category',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/project/delete-project-category',
                            data: {
                                id: categoryId
                            },
                            success: function (response) {
                                $("#modalDeleteProjectCategory").dialog('destroy');
                                $("#modalDeleteProjectCategory").empty();

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
                $("#modalDeleteProjectCategory").dialog('destroy');
                $("#modalDeleteProjectCategory").empty();
            }
        };

        $("#modalDeleteProjectCategory").load("/yongo/administration/project/delete-project-category-confirm/" + categoryId, [], function () {
            $("#modalDeleteProjectCategory").dialog(options);
            $("#modalDeleteProjectCategory").dialog("open");
        });
    });

    $('#btnDeleteWorkflow').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var workflowId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Workflow',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/workflow/delete',
                            data: {
                                id: workflowId
                            },
                            success: function (response) {
                                $("#modalDeleteWorkflow").dialog('destroy');
                                $("#modalDeleteWorkflow").empty();
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
                $("#modalDeleteWorkflow").dialog('destroy');
                $("#modalDeleteWorkflow").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteWorkflow").load("/yongo/administration/workflow/delete-confirm/" + workflowId + '/' + delete_possible, [], function () {
            $("#modalDeleteWorkflow").dialog(options);
            $("#modalDeleteWorkflow").dialog("open");
        });

    });

    $('#btnDeleteRelease').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var versionId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Project Version',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/project/delete-version',
                            data: {
                                release_id: versionId
                            },
                            success: function (response) {
                                $("#modalDeleteProjectRelease").dialog('destroy');
                                $("#modalDeleteProjectRelease").empty();
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
                $("#modalDeleteProjectRelease").dialog('destroy');
                $("#modalDeleteProjectRelease").empty();
            }
        };

        $("#modalDeleteProjectRelease").load("/yongo/administration/project/dialog-delete-version/" + versionId, [], function () {
            $("#modalDeleteProjectRelease").dialog(options);
            $("#modalDeleteProjectRelease").dialog("open");
        });
    });

    $('#btnDeleteProjectComponent').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }

        var componentId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Project Component',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/project/delete-component',
                            data: {
                                component_id: componentId
                            },
                            success: function (response) {
                                $("#modalDeleteProjectComponent").dialog('destroy');
                                $("#modalDeleteProjectComponent").empty();
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
                $("#modalDeleteProjectComponent").dialog('destroy');
                $("#modalDeleteProjectComponent").empty();
            }
        };

        $("#modalDeleteProjectComponent").load("/yongo/administration/project/dialog-delete-component/" + componentId, [], function () {
            $("#modalDeleteProjectComponent").dialog(options);
            $("#modalDeleteProjectComponent").dialog("open");
        });
    });

    function deleteProject(projectId, locationURL) {
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete project',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/delete-project',
                            data: {
                                project_id: projectId
                            },
                            success: function (response) {
                                $("#deleteClientProject").dialog('destroy');
                                $("#deleteClientProject").empty();
                                if (locationURL) {
                                    window.location.href = locationURL;
                                } else {
                                    location.reload();
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
                $("#deleteClientProject").dialog('destroy');
                $("#deleteClientProject").empty();
            }
        };
        var message = 'Are you sure you want to delete this project?<br />';
        message += 'All related information will be deleted (issues, comments, attachments, etc)';
        $("#deleteClientProject").html(message);
        $("#deleteClientProject").dialog(options);
        $("#deleteClientProject").dialog('open');
    }

    $('#btnDeleteClientProject').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return;
        }

        deleteProject(selected_rows[0]);
    });

    $('#btnDeleteClientProjectSummary').on('click', function (event) {
        event.preventDefault();

        deleteProject($('#project_id').val(), '/yongo/administration/projects');
    });

    $('#btnDeleteStepProperty').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var propertyId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Workflow Step Property',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/workflow/delete-set-property',
                            data: {
                                id: propertyId
                            },
                            success: function (response) {
                                $("#modalDeleteStepProperty").dialog('destroy');
                                $("#modalDeleteStepProperty").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteStepProperty").dialog('destroy');
                        $("#modalDeleteStepProperty").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteStepProperty").dialog('destroy');
                $("#modalDeleteStepProperty").empty();
            }
        };

        $("#modalDeleteStepProperty").load("/yongo/administration/workflow/delete-set-property-confirm/" + propertyId, [], function () {
            $("#modalDeleteStepProperty").dialog(options);
            $("#modalDeleteStepProperty").dialog("open");
        });
    });

    $("[id^='list_att_']").on('click', function (event) {
        event.preventDefault();
        var att_id = $(this).attr("id").replace('list_att_', '');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Confirm Delete Attachment',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/delete-attachement',
                            data: {
                                att_id: att_id
                            },
                            success: function (response) {
                                $("#deleteAttachment").dialog('destroy');
                                $("#deleteAttachment").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#deleteAttachment").dialog('destroy');
                        $("#deleteAttachment").empty();
                    }
                }
            ],
            close: function () {
                $("#deleteAttachment").dialog('destroy');
                $("#deleteAttachment").empty();
            }
        };

        var message = 'Are you sure you want to delete this attachment?<br />';
        $("#deleteAttachment").html(message);
        $("#deleteAttachment").dialog(options);
        $("#deleteAttachment").dialog('open');
    });

    $(document).on('click', "#radio_log_work_remaining_estimate_auto, #radio_log_work_remaining_estimate", function (event) {
        $('#log_remaining_work_set_to').prop('disabled', true);
        $('#log_work_remaining_reduce_by').prop('disabled', true);
    });

    $(document).on('click', "#radio_log_remaining_work_set_to", function (event) {
        $('#log_work_remaining_reduce_by').prop('disabled', true);
        $('#log_remaining_work_set_to').prop('disabled', false);
        $('#log_work_remaining_increase_by').prop('disabled', true);
    });

    $(document).on('click', "#radio_log_work_remaining_reduce_by", function (event) {
        $('#log_remaining_work_set_to').prop('disabled', true);
        $('#log_work_remaining_reduce_by').prop('disabled', false);
    });

    $(document).on('click', "#radio_log_work_remaining_increase_by", function (event) {
        $('#log_remaining_work_set_to').prop('disabled', true);
        $('#log_work_remaining_increase_by').prop('disabled', false);
    });

    $(document).on('click', "[id^='comment_']", function (event) {
        event.preventDefault();
        var comment_id = $(this).attr("id").replace('comment_', '');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Confirm delete comment',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/comment/delete',
                            data: {
                                id: comment_id
                            },
                            success: function (response) {
                                $("#deleteComment").dialog('destroy');
                                $("#deleteComment").empty();
                                var id = $('#issue_id').val();
                                $.ajax({
                                    type: "POST",
                                    url: '/yongo/comment-list',
                                    data: {
                                        id: id
                                    },
                                    success: function (response) {
                                        $('#tabContent').html(response);
                                    }
                                });
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#deleteComment").dialog('destroy');
                        $("#deleteComment").empty();
                    }
                }
            ],
            close: function () {
                $("#deleteComment").dialog('destroy');
                $("#deleteComment").empty();
            }
        };

        var message = 'Are you sure you want to delete this comment?<br />';
        $("#deleteComment").html(message);
        $("#deleteComment").dialog(options);
        $("#deleteComment").dialog('open');
    });

    $(document).on('click', "[id^='edit_comment']", function (event) {
        event.preventDefault();
        var commentId = $(this).attr("id").replace('edit_comment_', '');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Edit Comment',
            buttons: [
                {
                    text: "Save",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/issue/comment/update',
                            data: {
                                id: commentId,
                                content: $('#new_comment_edit_' + commentId).val()
                            },
                            success: function (response) {
                                $("#editCommentModal").dialog('destroy');
                                $("#editCommentModal").empty();
                                var issueId = $('#issue_id').val();
                                $.ajax({
                                    type: "POST",
                                    url: '/yongo/comment-list',
                                    data: {
                                        id: issueId
                                    },
                                    success: function (response) {
                                        $('#tabContent').html(response);
                                    }
                                });
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#editCommentModal").dialog('destroy');
                        $("#editCommentModal").empty();
                    }
                }
            ],
            close: function () {
                $("#editCommentModal").dialog('destroy');
                $("#editCommentModal").empty();
            }
        };

        $("#editCommentModal").load("/yongo/issue/comment/render-edit/" + commentId, [], function () {
            $("#editCommentModal").dialog(options);
            $("#editCommentModal").dialog("open");
        });
    });

    $('#btnDeleteScreenField').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Screen Field',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        var screen_data_id = selected_rows[0];

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/screen/delete-scheme-field',
                            data: {
                                screen_data_id: screen_data_id
                            },
                            success: function (response) {
                                $("#deleteScreenFieldModal").dialog('destroy');
                                $("#deleteScreenFieldModal").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#deleteScreenFieldModal").dialog('destroy');
                        $("#deleteScreenFieldModal").empty();
                    }
                }
            ],
            close: function () {
                $("#deleteScreenFieldModal").dialog('destroy');
                $("#deleteScreenFieldModal").empty();
            }
        };

        $("#deleteScreenFieldModal").load("/yongo/administration/screen/delete-scheme-field-confirm/" + selected_rows[0], [], function () {
            $("#deleteScreenFieldModal").dialog(options);
            $("#deleteScreenFieldModal").dialog("open");
        });
    });

    $("[id^='issue_transition_with_screen_']").click(function (event) {
        event.preventDefault();
        $('#menu_workflow').hide();
        var ids = $(this).attr("id").replace('issue_transition_with_screen_', '').split('_');
        var stepIdFrom = ids[0];
        var stepIdTo = ids[1];
        var workflowId = $('#workflow_used_id').val();
        var project_id = $('#project_id').val();
        var issueId = $('#issue_id').val();
        var title = $(this).text();

        var options = {
            title: title,

            buttons: [
                {
                    text: title,
                    click: function () {
                        // deal with mandatory flags
                        var responseMandatoryFields = dealWithMandatoryFieldsInModals();
                        if (responseMandatoryFields && responseMandatoryFields[1]) {
                            $('#errorsMandatoryFieldsNotPresentOnScreen').html(responseMandatoryFields[1]);
                            return
                        }

                        doTransitionWithScreen(issueId, stepIdFrom, stepIdTo, workflowId, 'transitionIssueModal', function () {
                            location.reload();
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/issue/cleanup',
                            data: {
                                issue_id: $('#issue_id').val()
                            },
                            success: function (response) {
                                $("#transitionIssueModal").dialog('destroy');
                                $("#transitionIssueModal").empty();
                            }
                        });
                    }
                }
            ],
            close: function () {
                $("#transitionIssueModal").dialog('close');
            }
        };

        $("#transitionIssueModal").load("/agile/render-transition-issue/" + workflowId + '/' + project_id + '/' + stepIdFrom + '/' + stepIdTo + '/' + issueId, [], function () {
            $("#transitionIssueModal").dialog(options);
            $("#transitionIssueModal").dialog("open");

            // call initialization file
            if (window.File && window.FileList && window.FileReader) {
                initializaFileUpload(issueId);
            }
            $(".ui-dialog-content .select2Input").select2();
            $(".ui-dialog-content .select2InputMedium").select2();
        });
    });

    $("#btnDeleteWorkflowStep").click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var stepId = selected_rows[0];
        var deletePossible = $('#delete_workflow_step_possible_' + stepId).val();
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Workflow Step',
            buttons: [
                {
                    text: 'Delete',
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/workflow/delete-step',
                            data: {
                                id: stepId
                            },
                            success: function (response) {
                                $("#modalDeleteWorkflowStep").dialog('close');
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteWorkflowStep").dialog('close');
                    }
                }
            ],
            close: function () {
                $("#transitionIssueModal").dialog('close');
            }
        };

        if (deletePossible == 0)
            options.buttons.shift();

        $("#modalDeleteWorkflowStep").load("/yongo/administration/workflow/delete-step-confirm/" + deletePossible, [], function () {
            $("#modalDeleteWorkflowStep").dialog(options);
            $("#modalDeleteWorkflowStep").dialog("open");
        });
    });

    function duplicateIssue(issueId) {
        $('#menu_more_actions').hide();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Duplicate Issue',
            buttons: [
                {
                    text: "Duplicate",
                    click: function () {

                        var issue_id = $('#issue_id').val();
                        var summary = $('#summary').val();

                        $.ajax({
                            type: "POST",
                            url: '/yongo/issue/duplicate',
                            data: {
                                issue_id: issue_id,
                                summary: summary
                            },
                            success: function (response) {
                                $("#duplicateIssueModal").dialog('destroy');
                                $("#duplicateIssueModal").empty();

                                window.location.href = '/yongo/issue/' + response;
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#duplicateIssueModal").dialog('destroy');
                        $("#duplicateIssueModal").empty();
                    }
                }
            ],
            close: function () {
                $("#duplicateIssueModal").dialog('destroy');
                $("#duplicateIssueModal").empty();
            }
        };

        $("#duplicateIssueModal").load("/yongo/issue/duplicate-dialog/" + issueId, [], function () {
            $("#duplicateIssueModal").dialog(options);
            $("#duplicateIssueModal").dialog("open");
        });
    }

    $('#issue_duplicate').click(function (event) {
        duplicateIssue($('#issue_id').val());
    });

    $(document).on('click', "[id^='issue_submenu_duplicate_']", function (event) {
        event.preventDefault();
        $('#contentMenuIssueSearchOptions').hide();
        var issueId = $(this).attr("id").replace('issue_submenu_duplicate_', '');

        duplicateIssue(issueId);
    });

    $(document).on('click', "[id^='issue_submenu_edit_']", function (event) {
        event.preventDefault();
        $('#contentMenuIssueSearchOptions').hide();
        var issueId = $(this).attr("id").replace('issue_submenu_edit_', '');

        editIssue(issueId);
    });

    $('#btnAssignUsersToRole').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Assign role users',
            buttons: [
                {
                    text: "Done",
                    click: function () {

                        var role_id = $('#role_id').val();
                        var user_arr = [];

                        $('#assigned_users > option').each(function (i, selected) {
                            user_arr[i] = $(this).val();
                        });

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/role/assign-users',
                            data: {
                                role_id: role_id,
                                user_arr: user_arr
                            },
                            success: function (response) {
                                $("#assignRoleUsers").dialog('destroy');
                                $("#assignRoleUsers").empty();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#assignRoleUsers").dialog('destroy');
                        $("#assignRoleUsers").empty();
                    }
                }
            ],
            close: function () {
                $("#assignRoleUsers").dialog('destroy');
                $("#assignRoleUsers").empty();
            }
        };

        $("#assignRoleUsers").load("/yongo/administration/role/assign-users-dialog/" + selected_rows[0], [], function () {
            $("#assignRoleUsers").dialog(options);
            $("#assignRoleUsers").dialog("open");
        });
    });

    $('#btnAssignGroupsToRole').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Assign role users',
            buttons: [
                {
                    text: "Done",
                    click: function () {

                        var role_id = $('#role_id').val();
                        var group_arr = [];

                        $('#assigned_groups > option').each(function (i, selected) {
                            group_arr[i] = $(this).val();
                        });

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/role/assign-groups',
                            data: {
                                role_id: role_id,
                                group_arr: group_arr
                            },
                            success: function (response) {
                                $("#assignRoleGroups").dialog('destroy');
                                $("#assignRoleGroups").empty();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#assignRoleGroups").dialog('destroy');
                        $("#assignRoleGroups").empty();
                    }
                }
            ],
            close: function () {
                $("#assignRoleGroups").dialog('destroy');
                $("#assignRoleGroups").empty();
            }
        };

        $("#assignRoleGroups").load("/yongo/administration/role/assign-groups-dialog/" + selected_rows[0], [], function () {
            $("#assignRoleGroups").dialog(options);
            $("#assignRoleGroups").dialog("open");
        });
    });

    $('#btnManageUsersInProjectRole').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var projectId = $('#project_id').val();
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Assign role users',
            buttons: [
                {
                    text: "Done",
                    click: function () {

                        var role_id = $('#role_id').val();
                        var user_arr = [];

                        $('#assigned_users > option').each(function (i, selected) {
                            user_arr[i] = $(this).val();
                        });

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/project/assign-roles',
                            data: {
                                role_id: role_id,
                                user_arr: user_arr,
                                project_id: projectId
                            },
                            success: function (response) {
                                $("#assignProjectUsersToRole").dialog('destroy');
                                $("#assignProjectUsersToRole").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#assignProjectUsersToRole").dialog('destroy');
                        $("#assignProjectUsersToRole").empty();
                    }
                }
            ],
            close: function () {
                $("#assignProjectUsersToRole").dialog('destroy');
                $("#assignProjectUsersToRole").empty();
            }
        };

        $("#assignProjectUsersToRole").load("/yongo/administration/project/assign-roles-dialog/" + projectId + "/" + selected_rows[0], [], function () {
            $("#assignProjectUsersToRole").dialog(options);
            $("#assignProjectUsersToRole").dialog("open");
        });
    });

    $('#btnManageGroupsInProjectRole').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var projectId = $('#project_id').val();
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Assign role users',
            buttons: [
                {
                    text: "Done",
                    click: function () {

                        var role_id = $('#role_id').val();
                        var group_arr = [];

                        $('#assigned_groups > option').each(function (i, selected) {
                            group_arr[i] = $(this).val();
                        });

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/project/assign-groups',
                            data: {
                                role_id: role_id,
                                group_arr: group_arr,
                                project_id: projectId
                            },
                            success: function (response) {
                                $("#assignProjectGroupsToRole").dialog('destroy');
                                $("#assignProjectGroupsToRole").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#assignProjectGroupsToRole").dialog('destroy');
                        $("#assignProjectGroupsToRole").empty();
                    }
                }
            ],
            close: function () {
                $("#assignProjectGroupsToRole").dialog('destroy');
                $("#assignProjectGroupsToRole").empty();
            }
        };

        $("#assignProjectGroupsToRole").load("/yongo/administration/project/assign-groups-dialog/" + projectId + "/" + selected_rows[0], [], function () {
            $("#assignProjectGroupsToRole").dialog(options);
            $("#assignProjectGroupsToRole").dialog("open");
        });
    });

    $('#btnAssignUserInGroup').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Assign group users',
            buttons: [
                {
                    text: "Done",
                    click: function () {

                        var group_id = $('#group_id').val();
                        var user_arr = [];

                        $('#assigned_users > option').each(function (i, selected) {
                            user_arr[i] = $(this).val();
                        });

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/group-assign-users',
                            data: {
                                group_id: group_id,
                                user_arr: user_arr
                            },
                            success: function (response) {
                                $("#assignUsersInGroup").dialog('destroy');
                                $("#assignUsersInGroup").empty();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#assignUsersInGroup").dialog('destroy');
                        $("#assignUsersInGroup").empty();
                    }
                }
            ],
            close: function () {
                $("#assignUsersInGroup").dialog('destroy');
                $("#assignUsersInGroup").empty();
            }
        };

        $("#assignUsersInGroup").load("/yongo/administration/group-assign-users-confirm/" + selected_rows[0], [], function () {
            $("#assignUsersInGroup").dialog(options);
            $("#assignUsersInGroup").dialog("open");
        });

    });

    $('#btnDeleteUserGroup').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete user group',
            buttons: [
                {
                    text: "Delete group",
                    click: function () {

                        var group_id = $('#group_id').val();
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/group/delete',
                            data: {
                                group_id: group_id
                            },
                            success: function (response) {
                                $("#deleteUserGroup").dialog('destroy');
                                $("#deleteUserGroup").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#deleteUserGroup").dialog('destroy');
                        $("#deleteUserGroup").empty();
                    }
                }
            ],
            close: function () {
                $("#deleteUserGroup").dialog('destroy');
                $("#deleteUserGroup").empty();
            }
        };

        $("#deleteUserGroup").load("/yongo/administration/group/delete-confirm/" + selected_rows[0], [], function () {
            $("#deleteUserGroup").dialog(options);
            $("#deleteUserGroup").dialog("open");
        });
    });

    $('#btnDeletePermRole').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete permission role',
            buttons: [
                {
                    text: "Delete role",
                    click: function () {

                        var perm_role_id = $('#perm_role_id').val();
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/delete-role',
                            data: {
                                perm_role_id: perm_role_id
                            },
                            success: function (response) {
                                $("#deletePermRole").dialog('destroy');
                                $("#deletePermRole").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#deletePermRole").dialog('destroy');
                        $("#deletePermRole").empty();
                    }
                }
            ],
            close: function () {
                $("#deletePermRole").dialog('destroy');
                $("#deletePermRole").empty();
            }
        };

        $("#deletePermRole").load("/yongo/administration/delete-role-confirm/" + selected_rows[0], [], function () {
            $("#deletePermRole").dialog(options);
            $("#deletePermRole").dialog("open");
        });
    });

    $("[id^='delete_notification_data_']").on('click', function (event) {
        event.preventDefault();

        var notification_scheme_data_id = $(this).attr("id").replace('delete_notification_data_', '');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Notification Scheme Data Confirmation',
            buttons: [
                {
                    text: "Delete Notification",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/notification-scheme/delete-data',
                            data: {
                                notification_scheme_data_id: notification_scheme_data_id
                            },
                            success: function (response) {
                                $("#deleteNotificationDataModal").dialog('destroy');
                                $("#deleteNotificationDataModal").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#deleteNotificationDataModal").dialog('destroy');
                        $("#deleteNotificationDataModal").empty();
                    }
                }
            ],
            close: function () {
                $("#deleteNotificationDataModal").dialog('destroy');
                $("#deleteNotificationDataModal").empty();
            }
        };

        $("#deleteNotificationDataModal").load("/yongo/administration/notification-scheme/delete-data-confirm/" + notification_scheme_data_id, [], function () {
            $("#deleteNotificationDataModal").dialog(options);
            $("#deleteNotificationDataModal").dialog("open");
        });
    });

    $("[id^='perm_delete_']").on('click', function (event) {
        event.preventDefault();

        var permission_scheme_data_id = $(this).attr("id").replace('perm_delete_', '');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Permission Data Confirmation',
            buttons: [
                {
                    text: "Delete Permission Data",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/permission-scheme/delete-data',
                            data: {
                                permission_scheme_data_id: permission_scheme_data_id
                            },
                            success: function (response) {
                                $("#deletePermissionData").dialog('destroy');
                                $("#deletePermissionData").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#deletePermissionData").dialog('destroy');
                        $("#deletePermissionData").empty();
                    }
                }
            ],
            close: function () {
                $("#deletePermissionData").dialog('destroy');
                $("#deletePermissionData").empty();
            }
        };

        $("#deletePermissionData").load("/yongo/administration/permission-scheme/delete-data-confirm/" + permission_scheme_data_id, [], function () {
            $("#deletePermissionData").dialog(options);
            $("#deletePermissionData").dialog("open");
        });
    });

    $("[id^='delete_security_data_']").on('click', function (event) {
        event.preventDefault();

        var security_scheme_data_id = $(this).attr("id").replace('delete_security_data_', '');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Security Level Data',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/security-scheme/delete-data',
                            data: {
                                id: security_scheme_data_id
                            },
                            success: function (response) {
                                $("#modalDeleteIssueSecuritySchemeLevelData").dialog('destroy');
                                $("#modalDeleteIssueSecuritySchemeLevelData").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteIssueSecuritySchemeLevelData").dialog('destroy');
                        $("#modalDeleteIssueSecuritySchemeLevelData").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteIssueSecuritySchemeLevelData").dialog('destroy');
                $("#modalDeleteIssueSecuritySchemeLevelData").empty();
            }
        };

        $("#modalDeleteIssueSecuritySchemeLevelData").load("/yongo/administration/security-scheme/delete-data-confirm/" + security_scheme_data_id, [], function () {
            $("#modalDeleteIssueSecuritySchemeLevelData").dialog(options);
            $("#modalDeleteIssueSecuritySchemeLevelData").dialog("open");
        });
    });

    $('#btnDeleteWorkflowTransition').click(function (event) {

        event.preventDefault();
        var transitionId = $('#transition_id').val();
        var workflowId = $('#workflow_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Transition',
            buttons: [
                {
                    text: "Delete Transition",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/workflow/delete-transition',
                            data: {
                                transition_id: transitionId
                            },
                            success: function (response) {
                                $("#deleteWorkflowTransitionModal").dialog('destroy');
                                $("#deleteWorkflowTransitionModal").empty();
                                window.location.href = '/yongo/administration/workflow/view-as-text/' + workflowId;
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#deleteWorkflowTransitionModal").dialog('destroy');
                        $("#deleteWorkflowTransitionModal").empty();
                    }
                }
            ],
            close: function () {
                $("#deleteWorkflowTransitionModal").dialog('destroy');
                $("#deleteWorkflowTransitionModal").empty();
            }
        };

        $("#deleteWorkflowTransitionModal").load("/yongo/administration/workflow/delete-transition-confirm/" + transitionId, [], function () {
            $("#deleteWorkflowTransitionModal").dialog(options);
            $("#deleteWorkflowTransitionModal").dialog("open");
        });
    });

    $('#btnDeleteStepOutgoingTransitions').click(function (event) {

        event.preventDefault();
        var stepId = $('#step_id').val();
        var workflowId = $('#workflow_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Outgoing Transitions',
            buttons: [
                {
                    text: "Delete Outgoing Transitions",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/workflow/delete-outgoing-transitions',
                            data: {
                                workflow_id: workflowId,
                                step_id: stepId
                            },
                            success: function (response) {
                                $("#deleteStepOutgoingTransitions").dialog('destroy');
                                $("#deleteStepOutgoingTransitions").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#deleteStepOutgoingTransitions").dialog('destroy');
                        $("#deleteStepOutgoingTransitions").empty();
                    }
                }
            ],
            close: function () {
                $("#deleteStepOutgoingTransitions").dialog('destroy');
                $("#deleteStepOutgoingTransitions").empty();
            }
        };

        $("#deleteStepOutgoingTransitions").load("/yongo/administration/workflow/delete-outgoing-transitions-confirm/" + stepId, [], function () {
            $("#deleteStepOutgoingTransitions").dialog(options);
            $("#deleteStepOutgoingTransitions").dialog("open");
        });
    });

    $('#btn_save_filter').click(function (event) {

        event.preventDefault();
        var val = $('#entity_id').val();
        var entityId = -1;
        if (val) {
            entityId = val;
        }
        var dialogTitle = 'Save Filter';
        if (entityId > 0) {
            dialogTitle = 'Update Filter';
        }

        var options = {
            dialogClass: "ubirimi-dialog",
            title: dialogTitle,
            buttons: [
                {
                    text: dialogTitle,
                    click: function () {
                        var filter_name = $('#filter_name').val();
                        var filter_description = $('#filter_description').val();

                        $.ajax({
                            type: "POST",
                            url: '/yongo/issue-search/save-filter',
                            data: {
                                id: entityId,
                                project_id: $('#project_id').val(),
                                filter_name: filter_name,
                                filter_description: filter_description,
                                filter_data: $('#filter_url').val()
                            },
                            success: function (response) {

                                $("#saveFilterModal").dialog('destroy');
                                $("#saveFilterModal").empty();
                                if (response != '-1') {
                                    var locationURL = window.location.href;
                                    locationURL = addParameterToURL(locationURL, 'filter=' + response);
                                    window.location.href = locationURL;
                                } else {
                                    location.reload();
                                }
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#saveFilterModal").dialog('destroy');
                        $("#saveFilterModal").empty();
                    }
                }
            ],
            close: function () {
                $("#saveFilterModal").dialog('destroy');
                $("#saveFilterModal").empty();
            }
        };

        $("#saveFilterModal").load("/yongo/issue-search/save-filter-dialog/" + entityId, [], function () {
            $("#saveFilterModal").dialog(options);
            $("#saveFilterModal").dialog("open");
        });
    });

    $('#btnDeleteFilter').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var filterId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete report',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/report/delete',
                            data: {
                                filter_id: filterId
                            },
                            success: function (response) {
                                $("#deleteFilterModal").dialog('destroy');
                                $("#deleteFilterModal").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#deleteFilterModal").dialog('destroy');
                        $("#deleteFilterModal").empty();
                    }
                }
            ],
            close: function () {
                $("#deleteFilterModal").dialog('destroy');
                $("#deleteFilterModal").empty();
            }
        };

        var delete_possible = $('#delete_filter_possible_' + filterId).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#deleteFilterModal").load("/yongo/report/delete-dialog/" + filterId + '/' + delete_possible, [], function () {
            $("#deleteFilterModal").dialog(options);
            $("#deleteFilterModal").dialog("open");
        });
    });

    $('#issue_add_subtask').click(function (event) {

        event.preventDefault();
        $('#menu_more_actions').hide();
        var issueId = $('#issue_id').val();
        var projectId = $('#project_id').val();

        function doReload() {
            location.reload();
        }

        createSubtask(issueId, projectId, doReload);
    });

    $('#issue_add_link').click(function (event) {

        event.preventDefault();
        $('#menu_more_actions').hide();
        var issueId = $('#issue_id').val();
        var projectId = $('#project_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Link',
            buttons: [
                {
                    text: "Link",
                    click: function () {
                        var linkChildIssue = $('#link_child_issue').val();
                        if (linkChildIssue) {
                            $.ajax({
                                type: "POST",
                                url: '/yongo/issue/link',
                                data: {
                                    id: issueId,
                                    link_type: $('#link_type').val(),
                                    linked_issues: $('#link_child_issue').val(),
                                    comment: $('#link_comment').val()
                                },
                                success: function (response) {
                                    $("#modalLinkIssue").dialog('destroy');
                                    $("#modalLinkIssue").empty();

                                    location.reload();
                                }
                            });
                        } else {
                            $('#linkWarning').html('You must select an issue to link to.')
                        }
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalLinkIssue").dialog('destroy');
                        $("#modalLinkIssue").empty();
                    }
                }
            ],
            close: function () {
                $("#modalLinkIssue").dialog('destroy');
                $("#modalLinkIssue").empty();
            }
        };

        var linkPossible = $('#link_possible').val();
        if (linkPossible == 0) {
            options.buttons.shift();
        }

        $("#modalLinkIssue").load("/yongo/issue/link-dialog/" + projectId + '/' + issueId + '/' + linkPossible, [], function () {
            $("#modalLinkIssue").dialog(options);
            $("#modalLinkIssue").dialog("open");
            $(".ui-dialog-content .select2Input").select2();
            $(".ui-dialog-content .select2InputSmall").select2();
        });
    });

    $(document).on('click', '#btnCreateIssue, #btnCreateIssueMenu', function (event) {

        event.preventDefault();

        createIssue();
    });

    $('#btnEditIssueFromDetail').click(function (event) {

        event.preventDefault();

        editIssue($('#issue_id').val());
    });

    function commentIssue(issueId) {
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Comment on Issue',
            buttons: [
                {
                    text: "Add Comment",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/comment/add',
                            data: {
                                id: issueId,
                                content: $('#issue_add_comment').val()
                            },
                            success: function (response) {
                                $("#addCommentModal").dialog('destroy');
                                $("#addCommentModal").empty();
                                loadIssueComments();
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
                $("#addCommentModal").dialog('destroy');
                $("#addCommentModal").empty();
            }
        };

        $("#addCommentModal").load("/yongo/issue/comment-dialog/" + issueId, [], function () {
            $("#addCommentModal").dialog(options);
            $("#addCommentModal").dialog("open");
        });
    }

    $('#btnEditIssueComment').click(function (event) {

        event.preventDefault();

        commentIssue($('#issue_id').val());
    });

    $('#btnEditIssueAssign').click(function (event) {
        assignIssue(event);
    });

    $('#btnIssueAssignToMe').click(function (event) {

        event.preventDefault();
        var issueId = $('#issue_id').val();

        $.ajax({
            type: "POST",
            url: '/yongo/issue/assign-to-me',
            data: {
                id: issueId
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $(document).on('click', "[id^='edit_issue_child_']", function (event) {
        event.preventDefault();

        var issueId = $(this).attr("id").replace('edit_issue_child_', '');
        editIssue(issueId);
    });

    $(document).on('click', "[id^='edit_work_log_']", function (event) {
        event.preventDefault();

        var workLogId = $(this).attr("id").replace('edit_work_log_', '');
        var issueId = $('#issue_id').val();
        var idSelectedSubTab = $('#is_tab_work_log').parent().children().filter('.active').attr('id');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Edit Work Log',
            buttons: [
                {
                    text: "Edit Work Log",
                    click: function () {

                        var timeSpent = jQuery.trim($('#log_work_time_spent').val());
                        var dateStarted = jQuery.trim($('#log_work_date_started').val());

                        // deal with mandatory flags
                        var mandatoryFieldsEmpty = false;
                        if (timeSpent == '') {
                            $('#error_time_spent').html('You must indicate the time spent working.');
                            mandatoryFieldsEmpty = true;
                        }
                        if (dateStarted == '') {
                            $('#error_date_started').html('You must specify a date on which the work occurred.');
                            mandatoryFieldsEmpty = true;
                        }

                        if (mandatoryFieldsEmpty) {
                            return
                        }
                        $.ajax({
                            type: "POST",
                            url: '/yongo/issue/validate-time-spent',
                            data: {
                                time: timeSpent
                            },
                            success: function (response) {
                                if (response == 'error') {
                                    $('#error_time_spent').html('Invalid time duration entered.');

                                    return
                                }

                                var remaining_estimate;
                                var estimate_strategy = $("input:radio[name='log_work_remaining_estimate']:checked").val();

                                if (estimate_strategy == 'adjust_automatically') {
                                    remaining_estimate = 'automatic';
                                } else if (estimate_strategy == 'existing_estimate') {
                                    remaining_estimate = 'existing';
                                } else if (estimate_strategy == 'set_to') {
                                    remaining_estimate = '=' + $('#log_remaining_work_set_to').val();
                                }

                                $.ajax({
                                    type: "POST",
                                    url: '/yongo/issue/edit-log-work',
                                    data: {
                                        id: workLogId,
                                        issue_id: issueId,
                                        time_spent: timeSpent,
                                        date_started: $('#log_work_date_started').val(),
                                        remaining: remaining_estimate,
                                        comment: $('#log_work_work_description').val()
                                    },
                                    success: function (response) {
                                        $('#issue_remaining_estimate').val(response);
                                        $("#modalLogWorkEdit").dialog('destroy');
                                        $("#modalLogWorkEdit").empty();
                                        if (idSelectedSubTab == 'is_tab_work_log')
                                            loadIssueWorkLog();

                                        $.ajax({
                                            type: "POST",
                                            url: '/yongo/issue/render-time-tracking-status',
                                            data: {
                                                id: issueId
                                            },
                                            success: function (response) {
                                                $('#ajax_time_tracking').html(response);
                                            }
                                        });
                                    }
                                });
                            }
                        })
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalLogWorkEdit").dialog('destroy');
                        $("#modalLogWorkEdit").empty();
                    }
                }
            ],
            close: function () {
                $("#modalLogWorkEdit").dialog('destroy');
                $("#modalLogWorkEdit").empty();
            }
        };

        var issueRemainingEstimate = $('#issue_remaining_estimate').val();

        if (issueRemainingEstimate == undefined) {
            issueRemainingEstimate = -1;
        }
        $("#modalLogWorkEdit").load("/yongo/issue/log-work-edit-dialog/" + workLogId + '/' + issueRemainingEstimate, [], function () {
            $("#modalLogWorkEdit").dialog(options);
            $("#modalLogWorkEdit").dialog("open");

            $('#log_work_date_started').datetimepicker({
                timeFormat: "hh:mm",
                dateFormat: "dd-mm-yy",
                ampm: false
            });
        });
    });

    $(document).on('click', "[id^='delete_work_log_']", function (event) {
        event.preventDefault();

        var workLogId = $(this).attr("id").replace('delete_work_log_', '');
        var issueId = $('#issue_id').val();
        var idSelectedSubTab = $('#is_tab_work_log').parent().children().filter('.active').attr('id');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Work Log',
            buttons: [
                {
                    text: "Delete Work Log",
                    click: function () {

                        var remaining_estimate;
                        var estimate_strategy = $("input:radio[name='log_work_remaining_estimate']:checked").val();

                        if (estimate_strategy == 'adjust_automatically') {
                            remaining_estimate = 'automatic';
                        } else if (estimate_strategy == 'existing_estimate') {
                            remaining_estimate = 'existing';
                        } else if (estimate_strategy == 'set_to') {
                            remaining_estimate = '=' + $('#log_remaining_work_set_to').val();
                        } else if (estimate_strategy == 'increase_by') {
                            remaining_estimate = '+' + $('#log_work_remaining_increase_by').val();
                        }

                        $.ajax({
                            type: "POST",
                            url: '/yongo/issue/delete-log-work',
                            data: {
                                id: workLogId,
                                issue_id: issueId,
                                remaining: remaining_estimate,
                                comment: $('#log_work_work_description').val()
                            },
                            success: function (response) {
                                $('#issue_remaining_estimate').val(response);

                                $("#modalLogWorkDelete").dialog('destroy');
                                $("#modalLogWorkDelete").empty();
                                if (idSelectedSubTab == 'is_tab_work_log')
                                    loadIssueWorkLog();

                                $.ajax({
                                    type: "POST",
                                    url: '/yongo/issue/render-time-tracking-status',
                                    data: {
                                        id: issueId
                                    },
                                    success: function (response) {
                                        $('#ajax_time_tracking').html(response);
                                    }
                                });
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalLogWorkDelete").dialog('destroy');
                        $("#modalLogWorkDelete").empty();
                    }
                }
            ],
            close: function () {
                $("#modalLogWorkDelete").dialog('destroy');
                $("#modalLogWorkDelete").empty();
            }
        };

        var issueRemainingEstimate = $('#issue_remaining_estimate').val();

        $("#modalLogWorkDelete").load("/yongo/issue/log-work-delete-dialog/" + workLogId + '/' + issueRemainingEstimate, [], function () {
            var $modalLogWorkDelete = $("#modalLogWorkDelete");
            $modalLogWorkDelete.dialog(options);
            $modalLogWorkDelete.dialog("open");

            $('#log_work_date_started').datetimepicker({
                timeFormat: "hh:mm",
                dateFormat: "dd-mm-yy",
                ampm: false
            });
        });
    });

    $('#add_issue_log_work, #mwnu_add_issue_log_work').click(function (event) {
        event.stopPropagation();
        event.preventDefault();

        $('.btn-group').removeClass('open');

        var issueId = $('#issue_id').val();
        var idSelectedSubTab = $('#is_tab_work_log').parent().children().filter('.active').attr('id');
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Log Work',
            buttons: [
                {
                    text: "Log Work",
                    click: function () {

                        var timeSpent = jQuery.trim($('#log_work_time_spent').val());
                        var dateStarted = jQuery.trim($('#log_work_date_started').val());

                        // deal with mandatory flags
                        var mandatoryFieldsEmpty = false;
                        if (timeSpent == '') {
                            $('#error_time_spent').html('You must indicate the time spent working.');
                            mandatoryFieldsEmpty = true;
                        }
                        if (dateStarted == '') {
                            $('#error_date_started').html('You must specify a date on which the work occurred.');
                            mandatoryFieldsEmpty = true;
                        }

                        if (mandatoryFieldsEmpty) {
                            return;
                        }
                        $.ajax({
                            type: "POST",
                            url: '/yongo/issue/validate-time-spent',
                            data: {
                                time: timeSpent
                            },
                            success: function (response) {

                                if (response == 'error') {
                                    $('#error_time_spent').html('Invalid time duration entered.');

                                    return
                                }
                                var remaining_estimate;
                                var estimate_strategy = $("input:radio[name='log_work_remaining_estimate']:checked").val();

                                if (estimate_strategy == 'adjust_automatically') {
                                    remaining_estimate = 'automatic';
                                } else if (estimate_strategy == 'existing_estimate') {
                                    remaining_estimate = 'existing';
                                } else if (estimate_strategy == 'set_to') {
                                    remaining_estimate = '=' + $('#log_remaining_work_set_to').val();
                                } else if (estimate_strategy == 'reduce_by') {
                                    remaining_estimate = '-' + $('#log_work_remaining_reduce_by').val();
                                }

                                $.ajax({
                                    type: "POST",
                                    url: '/yongo/issue/log-work',
                                    data: {
                                        id: issueId,
                                        time_spent: timeSpent,
                                        date_started: $('#log_work_date_started').val(),
                                        remaining: remaining_estimate,
                                        comment: $('#log_work_work_description').val()
                                    },
                                    success: function (response) {
                                        $('#issue_remaining_estimate').val(response);

                                        $("#modalLogWork").dialog('destroy');
                                        $("#modalLogWork").empty();
                                        if (idSelectedSubTab == 'is_tab_work_log')
                                            loadIssueWorkLog();
                                        else if (idSelectedSubTab == 'is_tab_history')
                                            loadIssueHistory();
                                        $.ajax({
                                            type: "POST",
                                            url: '/yongo/issue/render-time-tracking-status',
                                            data: {
                                                id: issueId
                                            },
                                            success: function (response) {
                                                $('#ajax_time_tracking').html(response);
                                            }
                                        });
                                    }
                                });
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
                $("#modalLogWork").dialog('destroy');
                $("#modalLogWork").empty();
            }
        };

        var issueRemainingEstimate = $('#issue_remaining_estimate').val();
        if (issueRemainingEstimate == undefined) {
            issueRemainingEstimate = -1;
        }
        $("#modalLogWork").load("/yongo/issue/log-work-dialog/" + issueRemainingEstimate, [], function () {
            $("#modalLogWork").dialog(options);
            $("#modalLogWork").dialog("open");

            $('#log_work_date_started').datetimepicker({
                timeFormat: "hh:mm",
                dateFormat: "dd-mm-yy",
                ampm: false
            });
        });
    });

    $('#btnDeleteCustomField').click(function (event) {

        event.preventDefault();
        var customFieldId = selected_rows[0];
        if (customFieldId == undefined) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Custom Field',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/custom-field/delete',
                            data: {
                                id: customFieldId
                            },
                            success: function (response) {
                                $("#modalDeleteCustomField").dialog('destroy');
                                $("#modalDeleteCustomField").empty();
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
                $("#modalDeleteCustomField").dialog('destroy');
                $("#modalDeleteCustomField").empty();
            }
        };

        $("#modalDeleteCustomField").load("/yongo/administration/custom-field/delete-confirm/" + customFieldId, [], function () {
            $("#modalDeleteCustomField").dialog(options);
            $("#modalDeleteCustomField").dialog("open");
        });
    });

    $('#sub_menu_issue_add_attachment').click(function (event) {

        event.preventDefault();
        $('#menu_more_actions').hide();
        var issueId = $('#issue_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Attach File',
            buttons: [
                {
                    text: "Attach",
                    click: function () {
                        if ($(this).hasClass('disabled')) {
                            return;
                        }

                        var attachments = $("[id^='attach_']");
                        var attach_ids = [];
                        for (var i = 0; i < attachments.length; i++) {
                            var check_id = attachments[i].getAttribute('id');
                            var checked = ($('#' + check_id).is(':checked'));
                            if (checked) {
                                attach_ids.push(attachments[i].getAttribute('id').replace('attach_', ''));
                            }
                        }

                        if (!attach_ids.length) {
                            attach_ids = null;
                        }
                        $.ajax({
                            type: "POST",
                            url: '/yongo/issue/save-attachement',
                            data: {
                                attach_ids: attach_ids,
                                issue_id: issueId,
                                comment: $('#attach_comment').val()
                            },
                            success: function (response) {
                                $("#modalEditIssueAttachFile").dialog('destroy');
                                $("#modalEditIssueAttachFile").empty();
                                //location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalEditIssueAttachFile").dialog('destroy');
                        $("#modalEditIssueAttachFile").empty();
                    }
                }
            ],
            close: function () {
                $("#modalEditIssueAttachFile").dialog('destroy');
                $("#modalEditIssueAttachFile").empty();
            }
        };

        $("#modalEditIssueAttachFile").load("/yongo/issue/attach-dialog/" + issueId, [], function () {
            $("#modalEditIssueAttachFile").dialog(options);
            $("#modalEditIssueAttachFile").dialog("open");

            // call initialization file
            if (window.File && window.FileList && window.FileReader) {
                initializaFileUpload(issueId);
            }
        });
    });

    $('#project_icon').click(function (event) {

        event.preventDefault();
        var projectId = $('#project_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Project Filters',
            buttons: [
                {
                    text: "Close",
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ],
            close: function () {
                $("#modalProjectFilters").dialog('destroy');
                $("#modalProjectFilters").empty();
            }
        };

        $("#modalProjectFilters").load("/yongo/project/filters/" + projectId, [], function () {
            $("#modalProjectFilters").dialog(options);
            $("#modalProjectFilters").dialog("open");
        });
    });

    $('#btnDeleteIssueResolution').click(function (event) {

        event.preventDefault();
        var elementClass = $(this).attr('class');
        if (elementClass.indexOf('disabled', 0) > -1) {
            return;
        }

        var resolutionId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Confirm Delete Issue Resolution',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/issue/delete-resolution',
                            data: {
                                id: resolutionId,
                                new_id: $('#modal_delete_resolution').val()
                            },
                            success: function (response) {
                                $("#modalDeleteIssueResolution").dialog('destroy');
                                $("#modalDeleteIssueResolution").empty();
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
                $("#modalDeleteIssueResolution").dialog('destroy');
                $("#modalDeleteIssueResolution").empty();
            }
        };

        $("#modalDeleteIssueResolution").load("/yongo/administration/issue/delete-resolution-confirm/" + resolutionId, [], function () {
            $("#modalDeleteIssueResolution").dialog(options);
            $("#modalDeleteIssueResolution").dialog("open");

        });
    });

    $('#btnDeleteIssuePriority').click(function (event) {
        event.preventDefault();
        var elementClass = $(this).attr('class');
        if (elementClass.indexOf('disabled', 0) > -1) {
            return;
        }
        var priorityId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Confirm Delete Issue Priority',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/issue/delete-priority',
                            data: {
                                id: priorityId,
                                new_id: $('#modal_delete_priority').val()
                            },
                            success: function (response) {
                                $("#modalDeleteIssuePriority").dialog('destroy');
                                $("#modalDeleteIssuePriority").empty();
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
                $("#modalDeleteIssuePriority").dialog('destroy');
                $("#modalDeleteIssuePriority").empty();
            }
        };

        $("#modalDeleteIssuePriority").load("/yongo/administration/issue/delete-priority-confirm/" + priorityId, [], function () {
            $("#modalDeleteIssuePriority").dialog(options);
            $("#modalDeleteIssuePriority").dialog("open");

        });
    });

    $('#btnDeleteIssueType').click(function (event) {

        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var typeId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Confirm Delete Issue Type',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/issue/delete-type',
                            data: {
                                id: typeId,
                                new_id: $('#modal_delete_type').val()
                            },
                            success: function (response) {
                                $("#modalDeleteIssueType").dialog('destroy');
                                $("#modalDeleteIssueType").empty();
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
                $("#modalDeleteIssueType").dialog('destroy');
                $("#modalDeleteIssueType").empty();
            }
        };

        $("#modalDeleteIssueType").load("/yongo/administration/issue/delete-type-confirm/" + typeId, [], function () {
            $("#modalDeleteIssueType").dialog(options);
            $("#modalDeleteIssueType").dialog("open");

        });
    });

    $('#btnDeleteFieldConfiguration').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Field Configuration',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        var fieldConfigurationId = selected_rows[0];

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/field-configuration/delete',
                            data: {
                                id: fieldConfigurationId
                            },
                            success: function (response) {
                                $("#modalDeleteFieldConfiguration").dialog('destroy');
                                $("#modalDeleteFieldConfiguration").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteFieldConfiguration").dialog('destroy');
                        $("#modalDeleteFieldConfiguration").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteFieldConfiguration").dialog('destroy');
                $("#modalDeleteFieldConfiguration").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteFieldConfiguration").load("/yongo/administration/field-configuration/delete-confirm/" + delete_possible, [], function () {
            $("#modalDeleteFieldConfiguration").dialog(options);
            $("#modalDeleteFieldConfiguration").dialog("open");
        });
    });

    $('#btnDeleteNotificationScheme').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var notificationSchemeId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Notification Scheme',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/notification-scheme/delete',
                            data: {
                                id: notificationSchemeId
                            },
                            success: function (response) {
                                $("#modalDeleteNotificationScheme").dialog('destroy');
                                $("#modalDeleteNotificationScheme").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteNotificationScheme").dialog('destroy');
                        $("#modalDeleteNotificationScheme").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteNotificationScheme").dialog('destroy');
                $("#modalDeleteNotificationScheme").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteNotificationScheme").load("/yongo/administration/notification-scheme/delete-confirm/" + notificationSchemeId + '/' + delete_possible, [], function () {
            $("#modalDeleteNotificationScheme").dialog(options);
            $("#modalDeleteNotificationScheme").dialog("open");
        });
    });

    $('#btnDeletePermissionScheme').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var permissionSchemeId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Permission Scheme',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/permission-scheme/delete',
                            data: {
                                id: permissionSchemeId
                            },
                            success: function (response) {
                                $("#modalDeletePermissionScheme").dialog('destroy');
                                $("#modalDeletePermissionScheme").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeletePermissionScheme").dialog('destroy');
                        $("#modalDeletePermissionScheme").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeletePermissionScheme").dialog('destroy');
                $("#modalDeletePermissionScheme").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeletePermissionScheme").load("/yongo/administration/permission-scheme/delete-confirm/" + permissionSchemeId + '/' + delete_possible, [], function () {
            $("#modalDeletePermissionScheme").dialog(options);
            $("#modalDeletePermissionScheme").dialog("open");
        });
    });

    $('#btnDeleteIssueTypeScheme').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Issue Type Scheme',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        var issueTypeSchemeId = selected_rows[0];

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/issue-type-scheme/delete',
                            data: {
                                id: issueTypeSchemeId
                            },
                            success: function (response) {
                                $("#modalDeleteIssueTypeScheme").dialog('destroy');
                                $("#modalDeleteIssueTypeScheme").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteIssueTypeScheme").dialog('destroy');
                        $("#modalDeleteIssueTypeScheme").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteIssueTypeScheme").dialog('destroy');
                $("#modalDeleteIssueTypeScheme").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteIssueTypeScheme").load("/yongo/administration/issue-type-scheme/delete-confirm/" + delete_possible, [], function () {
            $("#modalDeleteIssueTypeScheme").dialog(options);
            $("#modalDeleteIssueTypeScheme").dialog("open");
        });
    });

    $('#btnDeleteFieldConfigurationScheme').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Field Configuration Scheme',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        var fieldConfigurationSchemeId = selected_rows[0];

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/field-configuration/delete-scheme',
                            data: {
                                id: fieldConfigurationSchemeId
                            },
                            success: function (response) {
                                $("#modalDeleteFieldConfigurationScheme").dialog('destroy');
                                $("#modalDeleteFieldConfigurationScheme").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteFieldConfigurationScheme").dialog('destroy');
                        $("#modalDeleteFieldConfigurationScheme").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteFieldConfigurationScheme").dialog('destroy');
                $("#modalDeleteFieldConfigurationScheme").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteFieldConfigurationScheme").load("/yongo/administration/field-configuration/delete-scheme-confirm/" + delete_possible, [], function () {
            $("#modalDeleteFieldConfigurationScheme").dialog(options);
            $("#modalDeleteFieldConfigurationScheme").dialog("open");
        });
    });

    $('#btnDeleteScreen').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Screen',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        var screenId = selected_rows[0];

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/screen/delete',
                            data: {
                                id: screenId
                            },
                            success: function (response) {
                                $("#modalDeleteScreen").dialog('destroy');
                                $("#modalDeleteScreen").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteScreen").dialog('destroy');
                        $("#modalDeleteScreen").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteScreen").dialog('destroy');
                $("#modalDeleteScreen").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteScreen").load("/yongo/administration/screen/delete-confirm/" + delete_possible, [], function () {
            $("#modalDeleteScreen").dialog(options);
            $("#modalDeleteScreen").dialog("open");
        });
    });

    $('#btnDeleteScreenScheme').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Screen Scheme',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        var screenSchemeId = selected_rows[0];

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/screen/delete-scheme',
                            data: {
                                id: screenSchemeId
                            },
                            success: function (response) {
                                $("#modalDeleteScreenScheme").dialog('destroy');
                                $("#modalDeleteScreenScheme").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteScreenScheme").dialog('destroy');
                        $("#modalDeleteScreenScheme").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteScreenScheme").dialog('destroy');
                $("#modalDeleteScreenScheme").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteScreenScheme").load("/yongo/administration/screen/delete-scheme-confirm/" + delete_possible, [], function () {
            $("#modalDeleteScreenScheme").dialog(options);
            $("#modalDeleteScreenScheme").dialog("open");
        });
    });

    $('#btnDeleteIssueTypeScreenScheme').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Issue Type Screen Scheme',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        var issueTypeScreenSchemeId = selected_rows[0];

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/screen/delete-scheme-issue-type',
                            data: {
                                id: issueTypeScreenSchemeId
                            },
                            success: function (response) {
                                $("#modalDeleteIssueTypeScreenScheme").dialog('destroy');
                                $("#modalDeleteIssueTypeScreenScheme").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteIssueTypeScreenScheme").dialog('destroy');
                        $("#modalDeleteIssueTypeScreenScheme").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteIssueTypeScreenScheme").dialog('destroy');
                $("#modalDeleteIssueTypeScreenScheme").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteIssueTypeScreenScheme").load("/yongo/administration/screen/delete-scheme-issue-type-confirm/" + delete_possible, [], function () {
            $("#modalDeleteIssueTypeScreenScheme").dialog(options);
            $("#modalDeleteIssueTypeScreenScheme").dialog("open");
        });
    });

    $('#btnDeleteWorkflowScheme').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Workflow Scheme',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        var workflowScheme = selected_rows[0];

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/workflows/delete-scheme',
                            data: {
                                id: workflowScheme
                            },
                            success: function (response) {
                                $("#modalDeleteWorkflowScheme").dialog('destroy');
                                $("#modalDeleteWorkflowScheme").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteWorkflowScheme").dialog('destroy');
                        $("#modalDeleteWorkflowScheme").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteWorkflowScheme").dialog('destroy');
                $("#modalDeleteWorkflowScheme").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteWorkflowScheme").load("/yongo/administration/workflows/delete-scheme-confirm/" + delete_possible, [], function () {
            $("#modalDeleteWorkflowScheme").dialog(options);
            $("#modalDeleteWorkflowScheme").dialog("open");
        });
    });

    $('#btnDeleteWorkflowIssueTypeScheme').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return;
        }
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Workflow Issue Type Scheme',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        var workflowIssueTypeSchemeId = selected_rows[0];

                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/workflows/delete-issue-type-scheme',
                            data: {
                                id: workflowIssueTypeSchemeId
                            },
                            success: function (response) {
                                $("#modalDeleteWorkflowIssueTypeScheme").dialog('destroy');
                                $("#modalDeleteWorkflowIssueTypeScheme").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteWorkflowIssueTypeScheme").dialog('destroy');
                        $("#modalDeleteWorkflowIssueTypeScheme").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteWorkflowIssueTypeScheme").dialog('destroy');
                $("#modalDeleteWorkflowIssueTypeScheme").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + selected_rows[0]).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteWorkflowIssueTypeScheme").load("/yongo/administration/workflows/delete-issue-type-scheme-dialog/" + delete_possible, [], function () {
            $("#modalDeleteWorkflowIssueTypeScheme").dialog(options);
            $("#modalDeleteWorkflowIssueTypeScheme").dialog("open");
        });
    });

    $("[id^='deleteIssueLink_']").on('click', function (event) {
        event.preventDefault();

        var linkId = $(this).attr("id").replace('deleteIssueLink_', '');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Link',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/yongo/issue/delete-link',
                            data: {
                                id: linkId
                            },
                            success: function (response) {
                                $("#modalLinkIssueDelete").dialog('destroy');
                                $("#modalLinkIssueDelete").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalLinkIssueDelete").dialog('destroy');
                        $("#modalLinkIssueDelete").empty();
                    }
                }
            ],
            close: function () {
                $("#modalLinkIssueDelete").dialog('destroy');
                $("#modalLinkIssueDelete").empty();
            }
        };

        $("#modalLinkIssueDelete").load("/yongo/issue/delete-link-dialog", [], function () {
            $("#modalLinkIssueDelete").dialog(options);
            $("#modalLinkIssueDelete").dialog("open");
        });
    });

    $("#btnChangePreferences").on('click', function (event) {
        event.preventDefault();

        $('#contentMenuUserSummaryFilters').hide();

        var userId = $('#user_id').val();
        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Update User Preferences',
            buttons: [
                {
                    text: "Update",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/user/update-preferences',
                            data: {
                                id: userId,
                                issues_per_page: $('#user_issues_per_page').val(),
                                email_address: $('#email_address').val(),
                                notify_own_changes: $('#user_notify_own_changes').val(),
                                country_id: $('#user_country').val()
                            },
                            success: function (response) {
                                console.log(response);
                                if (response.email_already_exists) {
                                    $('#modal_user_preferences_email_error').html('Email address not available');
                                } else if (response.empty_email) {
                                    $('#modal_user_preferences_email_error').html('Empty email address');
                                } else if (response.email_not_valid) {
                                    $('#modal_user_preferences_email_error').html('Email address not valid');
                                } else {
                                    $("#modalChangePreferences").dialog('destroy');
                                    $("#modalChangePreferences").empty();
                                    location.reload();
                                }
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalChangePreferences").dialog('destroy');
                        $("#modalChangePreferences").empty();
                    }
                }
            ],
            close: function () {
                $("#modalChangePreferences").dialog('destroy');
                $("#modalChangePreferences").empty();
            }
        };

        $("#modalChangePreferences").load("/user/update-preferences-dialog/" + userId, [], function () {
            $("#modalChangePreferences").dialog(options);
            $("#modalChangePreferences").dialog("open");
            $('.ui-dialog-content .select2InputSmall').select2();
            $('.ui-dialog-content .select2InputMedium').select2();
        });
    });

    $('#btnDeletePostFunction').on('click', function (event) {
        event.preventDefault();
        var postFunctionDataId = selected_rows[0];
        if (!postFunctionDataId) {
            return;
        }
        var deletable = parseInt($('#post_function_deletable_' + postFunctionDataId).val());

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Workflow Post Function',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/workflow/delete-post-function-data',
                            data: {
                                id: postFunctionDataId
                            },
                            success: function (response) {
                                $("#modalDeletePostFunction").dialog('destroy');
                                $("#modalDeletePostFunction").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeletePostFunction").dialog('destroy');
                        $("#modalDeletePostFunction").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeletePostFunction").dialog('destroy');
                $("#modalDeletePostFunction").empty();
            }
        };

        if (deletable == 0) {
            options.buttons.shift();
        }

        $("#modalDeletePostFunction").load("/yongo/administration/workflow/delete-post-function-data-confirm/" + deletable, [], function () {
            $("#modalDeletePostFunction").dialog(options);
            $("#modalDeletePostFunction").dialog("open");
        });
    });

    $('#menu_keyboard_shortcuts').on('click', function (event) {

        event.preventDefault();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Keyboard Shortcuts',
            buttons: [
                {
                    text: "Close",
                    click: function () {
                        $("#modalKeyboardShortcuts").dialog('destroy');
                        $("#modalKeyboardShortcuts").empty();
                    }
                }
            ],
            close: function () {
                $("#modalKeyboardShortcuts").dialog('destroy');
                $("#modalKeyboardShortcuts").empty();
            }
        };

        $("#modalKeyboardShortcuts").load("/keyboard-shortcuts-dialog", [], function () {
            $("#modalKeyboardShortcuts").dialog(options);
            $("#modalKeyboardShortcuts").dialog("open");
        });
    });

    $('#btnShareIssue').click(function (event) {
        event.preventDefault();

        var issueId = $('#issue_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Share Issue',
            buttons: [
                {
                    text: "Share",
                    click: function () {
                        var users = $('#user_to_share').val();

                        if (users == null) {
                            $('#share_no_user_selected').html('Please select a user to share with');
                            return
                        }
                        $.ajax({
                            type: "POST",
                            url: '/yongo/issue/share',
                            data: {
                                id: issueId,
                                note: $('#share_issue_note').val(),
                                user_id: users
                            },
                            success: function (response) {
                                $("#modalShareIssue").dialog('destroy');
                                $("#modalShareIssue").empty();

                                $('#topMessageBox').html('Issue has been shared');
                                $('#topMessageBox').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
                                $('#topMessageBox').show().fadeOut(4000);

                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalShareIssue").dialog('destroy');
                        $("#modalShareIssue").empty();
                    }
                }
            ],
            close: function () {
                $("#modalShareIssue").dialog('destroy');
                $("#modalShareIssue").empty();
            }
        };

        $("#modalShareIssue").load("/yongo/issue/share-dialog/" + issueId, [], function () {
            $("#modalShareIssue").dialog(options);
            $("#modalShareIssue").dialog("open");
            $(".ui-dialog-content .select2Input").select2();
        });
    });

    function addFilterModal(filterId) {
        var addFilterSubscriptionModal = $("#addFilterSubscriptionModal");

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Add Filter Subscription',
            buttons: [
                {
                    text: "Add",
                    click: function () {

                        var postData = {};
                        if ($('#minute_chooser_choose').prop('checked')) {
                            postData.minute = $('#cron_minute').val();
                        } else {
                            postData.minute = ['*'];
                        }
                        if ($('#hour_chooser_choose').prop('checked')) {
                            postData.hour = $('#cron_hour').val();
                        } else {
                            postData.hour = ['*'];
                        }
                        if ($('#day_chooser_choose').prop('checked')) {
                            postData.day = $('#cron_day').val();
                        } else {
                            postData.day = ['*'];
                        }
                        if ($('#month_chooser_choose').prop('checked')) {
                            postData.month = $('#cron_month').val();
                        } else {
                            postData.month = ['*'];
                        }
                        if ($('#weekday_chooser_choose').prop('checked')) {
                            postData.weekday = $('#cron_weekday').val();
                        } else {
                            postData.weekday = ['*'];
                        }

                        postData.id = filterId;
                        postData.recipient_id = $('#recipient').val();
                        var emailWhenEmptyFlag = 0;

                        if ($('#email_when_empty').prop('checked')) {
                            emailWhenEmptyFlag = 1;
                        }
                        postData.email_when_empty = emailWhenEmptyFlag;

                        $.ajax({
                            type: "POST",
                            url: '/yongo/filter/subscription/add',
                            data: postData,
                            success: function (response) {
                                addFilterSubscriptionModal.dialog('destroy');
                                addFilterSubscriptionModal.empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        addFilterSubscriptionModal.dialog('destroy');
                        addFilterSubscriptionModal.empty();
                    }
                }
            ],
            close: function () {
                addFilterSubscriptionModal.dialog('destroy');
                addFilterSubscriptionModal.empty();
            }
        };

        addFilterSubscriptionModal.load("/yongo/filter/subscription/add/dialog", [], function () {

            addFilterSubscriptionModal.dialog(options);
            addFilterSubscriptionModal.dialog("open");
            $('.ui-dialog-content .select2Input').select2();
        });
    }

    $("[id^='add_filter_subscription_']").on('click', function (event) {
        event.preventDefault();

        var filterId = $(this).attr("id").replace('add_filter_subscription_', '');

        addFilterModal(filterId);
    });

    $("#btnNewFilterSubscription").on('click', function (event) {
        event.preventDefault();

        var filterId = $('#filter_id').val();

        addFilterModal(filterId);
    });
});