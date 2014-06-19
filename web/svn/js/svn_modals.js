$('document').ready(function () {

    $('#btnDeleteSvnRepo').on('click', function (event) {
        event.preventDefault();

        var elementClass = $(this).attr('class');
        if (elementClass.indexOf('disabled', 0) > -1)
            return

        var svnId = selected_rows[0];
        if (null == svnId) {
            svnId = $('#svn_repo_id').val();
        }

        if (null == svnId)
            return

        if (selected_rows[0] && selected_rows.length != 1)
            return

        var options = {
            modal: true,
            draggable: false,
            dialogClass: "ubirimi-dialog",
            width: "auto",
            stack: true,
            position: 'center',
            autoOpen: false,
            closeOnEscape: true,
            resizable: false,
            title: 'Delete SVN Repository',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/svn-hosting/repository/delete',
                            data: {
                                svn_id: svnId
                            },
                            success: function (response) {
                                $("#deleteSvnRepo").dialog('destroy');
                                $("#deleteSvnRepo").empty();
                                document.location.href = '/svn-hosting/administration/all-repositories';
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
                $("#deleteSvnRepo").dialog('destroy');
                $("#deleteSvnRepo").empty();
            }
        };

        var message = 'Are you sure you want to delete this SVN Repository?<br />';
        message += 'All related information will be deleted';

        $("#deleteSvnRepo").html(message);
        $("#deleteSvnRepo").dialog(options);
        $("#deleteSvnRepo").dialog('open');
    });

    $('#btnDeleteSvnUser').on('click', function (event) {
        event.preventDefault();

        if ($(this).hasClass('disabled'))
            return

        var options = {
            modal: true,
            draggable: false,
            dialogClass: "ubirimi-dialog",
            width: "auto",
            stack: true,
            position: 'center',
            autoOpen: false,
            closeOnEscape: true,
            resizable: false,
            title: 'Delete SVN User Repository',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/svn-hosting/administration/repository/user/delete',
                            data: {
                                id: selected_rows[0]
                            },
                            success: function (response) {
                                $("#deleteSvnUser").dialog('destroy');
                                $("#deleteSvnUser").empty();
                                document.location.href = '/svn-hosting/administration/repository/users/' + $('#repo_id').val();
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
                $("#deleteSvnUser").dialog('destroy');
                $("#deleteSvnUser").empty();
            }
        };

        var message = 'Are you sure you want to delete this SVN User?<br />';
        message += 'All related information will be deleted';

        $("#deleteSvnUser").html(message);
        $("#deleteSvnUser").dialog(options);
        $("#deleteSvnUser").dialog('open');
    });

    $('#btnSetPermissionsSvnUser').on('click', function (event) {
        event.preventDefault();

        var userId = selected_rows[0];
        if (!userId)
            userId = $('#user_id').val();

        if (!userId)
            return

        if ($(this).hasClass('disabled'))
            return

        var repoId = $('#repo_id').val();

        var options = {
            modal: true,
            draggable: false,
            dialogClass: "ubirimi-dialog",
            width: "auto",
            stack: true,
            position: 'center',
            autoOpen: false,
            closeOnEscape: true,
            resizable: false,
            title: 'Set SVN permissions for selected user',
            buttons: [
                {
                    text: "Set Permissions",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/svn-hosting/administration/repository/user/set-permissions',
                            data: {
                                has_read: $('#has_read').is(':checked') ? 1 : 0,
                                has_write: $('#has_write').is(':checked') ? 1 : 0,
                                id: userId,
                                repo_id: repoId
                            },
                            success: function (response) {
                                $("#modalSVNPermissionsUser").dialog('destroy');
                                $("#modalSVNPermissionsUser").empty();

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
                $("#modalSVNPermissionsUser").dialog('destroy');
                $("#modalSVNPermissionsUser").empty();
            }
        };

        $("#modalSVNPermissionsUser").load("/svn-hosting/administration/repository/user/set-permissions-confirm/" + userId + '/' + repoId, [], function () {
            $("#modalSVNPermissionsUser").dialog(options);
            $("#modalSVNPermissionsUser").dialog("open");
        });
    });

    $('#btnChangePasswordSvnUser').on('click', function (event) {
        event.preventDefault();

        if ($(this).hasClass('disabled'))
            return

        var userId = $('#user_id').val();
        if (userId == undefined) {
            userId = selected_rows[0];
        }
        var repoId = $('#repo_id').val();

        var options = {
            modal: true,
            draggable: false,
            dialogClass: "ubirimi-dialog",
            width: "auto",
            stack: true,
            position: 'center',
            autoOpen: false,
            closeOnEscape: true,
            resizable: false,
            title: 'Set SVN password for the selected uer',
            buttons: [
                {
                    text: "Set Password",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/svn-hosting/administration/repository/user/change-password-confirm/' + userId + '/' + repoId,
                            data: {
                                password: $('#password').val(),
                                password_again: $('#password_again').val(),
                                id: userId,
                                repo_id: repoId
                            },
                            success: function (response) {
                                if (1 == response) {
                                    $("#modalSVNChangePassword").dialog('destroy');
                                    $("#modalSVNChangePassword").empty();

                                    location.reload();
                                }
                                else {
                                    $('#modalSVNChangePassword').html(response);
                                }
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalSVNChangePassword").dialog('destroy');
                        $("#modalSVNChangePassword").empty();
                    }
                }
            ],
            close: function () {
                $("#modalSVNChangePassword").dialog('destroy');
                $("#modalSVNChangePassword").empty();
            }
        };

        $("#modalSVNChangePassword").load("/svn-hosting/administration/repository/user/change-password-confirm/" + userId + '/' + repoId , [], function () {
            $("#modalSVNChangePassword").dialog(options);
            $("#modalSVNChangePassword").dialog("open");
        });
    });

    $('#btnImportUsersSvn').click(function (event) {
        event.preventDefault();

        var importOptions = {
            width: 450,
            title: 'Import existing users to current repository',
            buttons: [
                {
                    text: "Import",
                    click: function () {

                        var users = [];
                        $('#import_users :selected').each(function (i, selected) {
                            users[i] = $(selected).val();
                        });

                        $.ajax({
                            type: "POST",
                            url: '/svn-hosting/administration/repository/user/import',
                            data: {
                                users: users
                            },
                            success: function (response) {
                                $("#modalSVNImportUsers").dialog('destroy');
                                $("#modalSVNImportUsers").empty();
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
                $("#modalSVNImportUsers").dialog('destroy');
                $("#modalSVNImportUsers").empty();
            }
        };

        $.ajax({
            type: "GET",
            url: '/svn-hosting/administration/repository/user/import-confirm',
            success: function (response) {
                if (0 == response) {
                    alert('There are no users to import.');
                }
                else {
                    $("#modalSVNImportUsers").html(response);
                    $("#modalSVNImportUsers").dialog(importOptions);
                    $("#modalSVNImportUsers").dialog("open");
                }
            }
        });
    });

    $('#btnDeleteSvnAdministrator').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1)
            return

        var options = {
            modal: true,
            draggable: false,
            dialogClass: "ubirimi-dialog",
            width: "auto",
            stack: true,
            position: 'center',
            autoOpen: false,
            closeOnEscape: true,
            resizable: false,
            title: 'Delete SVN Administrator',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        var userId = selected_rows[0];

                        $.ajax({
                            type: "POST",
                            url: '/svn-hosting/administration/delete-administrator',
                            data: {
                                id: userId
                            },
                            success: function (response) {
                                $("#modalDeleteSVNAdministrator").dialog('destroy');
                                $("#modalDeleteSVNAdministrator").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteSVNAdministrator").dialog('destroy');
                        $("#modalDeleteSVNAdministrator").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteSVNAdministrator").dialog('destroy');
                $("#modalDeleteSVNAdministrator").empty();
            }
        };

        var delete_possible = $('#delete_possible').val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteSVNAdministrator").load("/svn-hosting/administration/delete-administrator-confirm/" + selected_rows[0], [], function () {
            $("#modalDeleteSVNAdministrator").dialog(options);
            $("#modalDeleteSVNAdministrator").dialog("open");
        });
    });
})