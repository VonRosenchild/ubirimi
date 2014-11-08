$('document').ready(function () {

    $('#btnUserChangePassword').click(function (event) {

        event.preventDefault();
        var userId = $('#user_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Change Password',
            buttons: [
                {
                    text: "Change Password",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/general/user/change-password',
                            data: {
                                user_id: $('#user_id').val(),
                                current_password: $('#current_password').val(),
                                new_password: $('#new_password').val(),
                                confirm_password: $('#confirm_password').val()
                            },
                            success: function (response) {
                                $('#passwords_not_match').html('');
                                $('#current_password_wrong').html('');

                                if (response == 'password_mismatch') {
                                    $('#passwords_not_match').html('Passwords do not match.');
                                } else if (response == 'current_password_wrong') {
                                    $('#current_password_wrong').html('Current password wrong.');
                                } else if (response == 'password_too_short') {
                                    $('#passwords_not_match').html('New password too short');
                                } else {
                                    $("#modalChangePassword").dialog('destroy');
                                    $("#modalChangePassword").empty();
                                    $('#userDataUpdated').html('User information updated successfully.');
                                    $('#userDataUpdated').addClass('marginBottom');
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
                $("#modalChangePassword").dialog('destroy');
                $("#modalChangePassword").empty();
            }
        };

        $("#modalChangePassword").load("/general/user/change-password-dialog/" + userId, [], function () {
            $("#modalChangePassword").dialog(options);
            $("#modalChangePassword").dialog("open");
        });
    });

    $('#btnAssignUserToGroup, #btnAssignUserToGroupDocumentador').click(function (event) {
        event.preventDefault();

        if (selected_rows.length != 1)
            return;

        var userId = selected_rows[0];
        var productId = $('#product_id').val();

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Assign User to Group',
            buttons: [
                {
                    text: "Assign",
                    click: function () {
                        var assigned_groups = [];
                        $('#assigned_groups > option').each(function(i, selected){
                            assigned_groups[i] = $(this).val();
                        });

                        if (assigned_groups.length == 0)
                            assigned_groups = -1;

                        $.ajax({
                            type: "POST",
                            url: '/general/user/assign-groups',
                            data: {
                                user_id: userId,
                                assigned_groups: assigned_groups
                            },
                            success: function (response) {
                                window.location.reload();
//                                $("#modalAssignUserToGroup").dialog('destroy');
//                                $("#modalAssignUserToGroup").empty();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalAssignUserToGroup").dialog('destroy');
                        $("#modalAssignUserToGroup").empty();
                    }
                }
            ],
            close: function () {
                $("#modalAssignUserToGroup").dialog('destroy');
                $("#modalAssignUserToGroup").empty();
            }
        };

        $("#modalAssignUserToGroup").load("/general/user/assign-groups-dialog/" + userId + '/' + productId, [], function () {
            $("#modalAssignUserToGroup").dialog(options);
            $("#modalAssignUserToGroup").dialog("open");
        });
    });
});