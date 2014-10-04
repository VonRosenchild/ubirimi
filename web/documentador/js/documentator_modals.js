$('document').ready(function () {

    function deleteSpace(spaceId, locationHref) {

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Space',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/documentador/space/delete',
                            data: {
                                id: spaceId
                            },
                            success: function (response) {
                                $("#modalDeleteSpace").dialog('destroy');
                                $("#modalDeleteSpace").empty();
                                if (locationHref) {
                                    window.location.href = locationHref;
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
                $("#modalDeleteSpace").dialog('destroy');
                $("#modalDeleteSpace").empty();
            }
        };

        $("#modalDeleteSpace").load("/documentador/space/confirm-delete", [], function () {
            $("#modalDeleteSpace").dialog(options);
            $("#modalDeleteSpace").dialog("open");
        });
    }

    $('#btnDeleteSpaceFromSpaceTools, #btnDeleteSpaceFromPages').on('click', function (event) {
        var spaceId = $('#space_id').val();

        deleteSpace(spaceId, '/documentador/administration/spaces');
    });

    $('#btnDeleteSpace').on('click', function (event) {
        event.preventDefault();

        if (!selected_rows.length)
            return;

        var spaceId = selected_rows[0];

        deleteSpace(spaceId);
    });

    $('#doc_page_remove, #btnDeletePage').on('click', function (event) {
        event.preventDefault();
        $('#menu_page_tools').hide();

        var pageId = $('#entity_id').val();
        if (pageId === undefined)
            pageId = selected_rows[0];

        if (pageId === undefined)
            return;

        if (selected_rows.length > 1) {
            return;
        }
        var spaceId = $('#space_id').val();

        var options = {
            title: 'Remove Page',
            buttons: [
                {
                    text: "Remove",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/documentador/page/remove',
                            data: {
                                id: pageId,
                                space_id: spaceId
                            },
                            success: function (response) {
                                $("#modalRemovePage").dialog('destroy');
                                $("#modalRemovePage").empty();
                                window.location.href = '/documentador/pages/' + spaceId;
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
                $("#modalRemovePage").dialog('destroy');
                $("#modalRemovePage").empty();
            }
        };

        $("#modalRemovePage").load("/documentador/page/confirm-remove", [], function () {
            $("#modalRemovePage").dialog(options);
            $("#modalRemovePage").dialog("open");
        });
    });

    $('#btnRemoveRevision').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return;
        }

        var revisionId = selected_rows[0];
        var revisionNR = $('#revision_' + revisionId).val();

        var pageId = $('#entity_id').val();

        var options = {
            title: 'Remove Revision',
            buttons: [
                {
                    text: "Remove",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/documentador/page/remove-revision',
                            data: {
                                id: revisionId
                            },
                            success: function (response) {
                                $("#modalRemoveRevision").dialog('destroy');
                                $("#modalRemoveRevision").empty();
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
                $("#modalRemoveRevision").dialog('destroy');
                $("#modalRemoveRevision").empty();
            }
        };

        $("#modalRemoveRevision").load("/documentador/page/confirm-remove-revision/" + pageId + '/' + revisionNR, [], function () {
            $("#modalRemoveRevision").dialog(options);
            $("#modalRemoveRevision").dialog("open");
        });
    });

    $('#btnRestoreRevision').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return;
        }

        var revisionId = selected_rows[0];
        var revisionNR = $('#revision_' + revisionId).val();
        var pageId = $('#entity_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Restore Revision',
            buttons: [
                {
                    text: "Restore",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/documentador/page/restore-revision',
                            data: {
                                id: revisionId,
                                entity_id: pageId
                            },
                            success: function (response) {
                                $("#modalRestoreRevision").dialog('destroy');
                                $("#modalRestoreRevision").empty();
                                window.location.href = '/documentador/page/view/' + pageId
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
                $("#modalRestoreRevision").dialog('destroy');
                $("#modalRestoreRevision").empty();
            }
        };

        $("#modalRestoreRevision").load("/documentador/page/confirm-restore-revision/" + revisionNR, [], function () {
            $("#modalRestoreRevision").dialog(options);
            $("#modalRestoreRevision").dialog("open");
        });
    });

    $(document).on('click', "#btnNewDocumentatorPage, #btnDocumentatorCreate", function (event) {

        event.preventDefault();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Select Space and Type for Page',
            buttons: [
                {
                    text: "Create",
                    click: function () {

                        var spaceId = $('#new_page_space').val();

                        var parentEntityId = $('#entity_id').val();
                        if (parentEntityId == undefined)
                            parentEntityId = -1;

                        if (parentEntityId != -1) {
                            var currentSelectedSpace = $('#space_id').val();
                            if (currentSelectedSpace != spaceId)
                                parentEntityId = -1;
                        }

                        var type = $("input[type='radio']:checked").val();
                        if (type == 'blank_page') {
                            if (parentEntityId == -1) {
                                window.location.href = '/documentador/spaces/add-page/' + spaceId;
                            } else {
                                window.location.href = '/documentador/spaces/add-page/' + spaceId + '/' + parentEntityId;
                            }
                        } else if (type == 'file_list') {
                            // ask for name and description
                            $("#modalNewPage").dialog('destroy');
                            $("#modalNewPage").empty();

                            var options = {
                                dialogClass: "ubirimi-dialog",
                                title: 'Create File List',
                                buttons: [
                                    {
                                        text: "Create File List",
                                        click: function () {
                                            $.ajax({
                                                type: "POST",
                                                url: '/documentador/page/add-filelist',
                                                data: {
                                                    name: $('#entity_name').val(),
                                                    description: $('#entity_description').val(),
                                                    type: type,
                                                    parent_id: parentEntityId,
                                                    space_id: spaceId
                                                },
                                                success: function (response) {
                                                    $("#modalNewPageDetails").dialog('destroy');
                                                    $("#modalNewPageDetails").empty();
                                                    window.location.href = '/documentador/page/view/' + response;
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
                                    $("#modalNewPageDetails").dialog('destroy');
                                    $("#modalNewPageDetails").empty();
                                }
                            };

                            $("#modalNewPageDetails").load("/documentador/page/confirm-page-details/" + type, [], function () {
                                $("#modalNewPageDetails").dialog(options);
                                $("#modalNewPageDetails").dialog("open");
                            });

                        }
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalNewPage").dialog('destroy');
                        $("#modalNewPage").empty();
                    }
                }
            ],
            close: function () {
                $("#modalNewPage").dialog('destroy');
                $("#modalNewPage").empty();
            }
        };

        var defaultSpaceId = $('#space_id').val();
        if (defaultSpaceId == undefined) {
            defaultSpaceId = 0;
        }
        $("#modalNewPage").load("/documentador/new-page-dialog/" + defaultSpaceId, [], function () {
            $("#modalNewPage").dialog(options);
            $("#modalNewPage").dialog("open");
            $('.select2InputMedium').select2();
        });
    });

    $('#btnDeleteGroupDocumentator').on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return;
        }

        var groupId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete User Group',
            buttons: [
                {
                    text: "Delete Group",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/documentador/administration/group/delete',
                            data: {
                                id: groupId
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

        $("#deleteUserGroup").load("/documentador/administration/group/delete-confirm/" + selected_rows[0], [], function () {
            $("#deleteUserGroup").dialog(options);
            $("#deleteUserGroup").dialog("open");
        });
    });

    $('#btnPurgeAll').on('click', function (event) {
        event.preventDefault();

        var spaceId = $('#space_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Purge All',
            buttons: [
                {
                    text: "Purge All",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/documentador/administration/space/purge-all',
                            data: {
                                id: spaceId
                            },
                            success: function (response) {
                                $("#modalPurgeAll").dialog('destroy');
                                $("#modalPurgeAll").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalPurgeAll").dialog('destroy');
                        $("#modalPurgeAll").empty();
                    }
                }
            ],
            close: function () {
                $("#modalPurgeAll").dialog('destroy');
                $("#modalPurgeAll").empty();
            }
        };

        $("#modalPurgeAll").load("/documentador/administration/space/purge-all-confirm/" + spaceId, [], function () {
            $("#modalPurgeAll").dialog(options);
            $("#modalPurgeAll").dialog("open");
        });
    });

    $(document).on('click', "[id^='delete_doc_file_']", function (event) {
        event.preventDefault();
        var fileId = $(this).attr("id").replace('delete_doc_file_', '');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete File Revision',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/documentador/delete-file',
                            data: {
                                id: fileId
                            },
                            success: function (response) {
                                $("#modalDeleteFile").dialog('destroy');
                                $("#modalDeleteFile").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteFile").dialog('destroy');
                        $("#modalDeleteFile").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteFile").dialog('destroy');
                $("#modalDeleteFile").empty();
            }
        };

        $("#modalDeleteFile").load("/documentador/dialog/delete-file/" + fileId, [], function () {
            $("#modalDeleteFile").dialog(options);
            $("#modalDeleteFile").dialog("open");
        });
    });

    $(document).on('click', "[id^='delete_doc_attachment_']", function (event) {
        event.preventDefault();
        var attachmentId = $(this).attr("id").replace('delete_doc_attachment_', '');

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Attachment',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/documentador/delete-attachment',
                            data: {
                                id: attachmentId
                            },
                            success: function (response) {
                                $("#modalDeleteAttachment").dialog('destroy');
                                $("#modalDeleteAttachment").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteAttachment").dialog('destroy');
                        $("#modalDeleteAttachment").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteAttachment").dialog('destroy');
                $("#modalDeleteAttachment").empty();
            }
        };

        $("#modalDeleteAttachment").load("/documentador/dialog/delete-attachment/" + attachmentId, [], function () {
            $("#modalDeleteAttachment").dialog(options);
            $("#modalDeleteAttachment").dialog("open");
        });
    });

    $('#btnPageRestore').on('click', function (event) {
        event.preventDefault();

        if ($(this).hasClass('disabled')) {
            return;
        }

        var pageId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Restore Page',
            buttons: [
                {
                    text: "Restore Page",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/documentador/administration/page/restore',
                            data: {
                                id: pageId
                            },
                            success: function (response) {
                                $("#modalPageRestore").dialog('destroy');
                                $("#modalPageRestore").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalPageRestore").dialog('destroy');
                        $("#modalPageRestore").empty();
                    }
                }
            ],
            close: function () {
                $("#modalPageRestore").dialog('destroy');
                $("#modalPageRestore").empty();
            }
        };

        $("#modalPageRestore").load("/documentador/administration/page/restore-confirm/" + pageId, [], function () {
            $("#modalPageRestore").dialog(options);
            $("#modalPageRestore").dialog("open");
        });
    });

    $('#btnPagePurge').on('click', function (event) {
        event.preventDefault();

        if ($(this).hasClass('disabled')) {
            return;
        }

        var pageId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Purge Page',
            buttons: [
                {
                    text: "Purge Page",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/documentador/administration/page/purge',
                            data: {
                                id: pageId
                            },
                            success: function (response) {
                                $("#modalPagePurge").dialog('destroy');
                                $("#modalPagePurge").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalPagePurge").dialog('destroy');
                        $("#modalPagePurge").empty();
                    }
                }
            ],
            close: function () {
                $("#modalPagePurge").dialog('destroy');
                $("#modalPagePurge").empty();
            }
        };

        $("#modalPagePurge").load("/documentador/administration/page/purge-confirm/" + pageId, [], function () {
            $("#modalPagePurge").dialog(options);
            $("#modalPagePurge").dialog("open");
        });
    });

    $('#btnAssignUserInGroupDocumentator').on('click', function (event) {
        event.preventDefault();

        if ($(this).hasClass('disabled'))
            return;

        var groupId = selected_rows[0];

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Assign Users to Group',
            buttons: [
                {
                    text: "Assign Users",
                    click: function () {

                        var group_id = $('#group_id').val();
                        var user_arr = [];

                        $('#assigned_users > option').each(function (i, selected) {
                            user_arr[i] = $(this).val();
                        });

                        $.ajax({
                            type: "POST",
                            url: '/documentador/administration/group/assign-users',
                            data: {
                                group_id: group_id,
                                user_arr: user_arr
                            },
                            success: function (response) {
                                $("#assignUsersInGroup").dialog('destroy');
                                $("#assignUsersInGroup").empty();
                                location.reload();
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

        $("#assignUsersInGroup").load("/documentador/administration/group/dialog-assign-users/" + groupId, [], function () {
            $("#assignUsersInGroup").dialog(options);
            $("#assignUsersInGroup").dialog("open");
        });
    });

    $(document).on('click', "[id^='entity_delete_comment_']", function (event) {
        event.preventDefault();

        var commentId = $(this).attr("id").replace('entity_delete_comment_', '');

        var options = {
            title: 'Delete Comment',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/documentador/comment/delete',
                            data: {
                                id: commentId
                            },
                            success: function (response) {
                                $("#modalDeleteComment").dialog('destroy');
                                $("#modalDeleteComment").empty();
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
                $("#modalDeleteComment").dialog('destroy');
                $("#modalDeleteComment").empty();
            }
        };

        $("#modalDeleteComment").load("/documentador/comment/dialog-delete/" + commentId, [], function () {
            $("#modalDeleteComment").dialog(options);
            $("#modalDeleteComment").dialog("open");
        });
    });
});