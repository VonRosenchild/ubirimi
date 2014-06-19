$('document').ready(function () {

    $('#btnDeleteUser').click(function (event) {
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
            title: 'Delete user',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        var user_id = selected_rows[0];

                        $.ajax({
                            type: "POST",
                            url: '/general-settings/delete-user',
                            data: {
                                user_id: user_id
                            },
                            success: function (response) {
                                $("#modalDeleteUser").dialog('destroy');
                                $("#modalDeleteUser").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteUser").dialog('destroy');
                        $("#modalDeleteUser").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteUser").dialog('destroy');
                $("#modalDeleteUser").empty();
            }
        };

        $("#modalDeleteUser").load("/general-settings/delete-user-confirm/" + selected_rows[0], [], function () {
            var delete_possible = $('#delete_possible').val();

            if (0 == delete_possible) {
                options.buttons.shift();
            }

            $("#modalDeleteUser").dialog(options);
            $("#modalDeleteUser").dialog("open");
        });
    });

    $('#btnDeleteSMTPServer').on('click', function (event) {
        event.preventDefault();

        var smtpId = $('#smtp_id').val();
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
            title: 'Delete SMTP Server',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/general-settings/delete-smtp-server',
                            data: {
                                id: smtpId
                            },
                            success: function (response) {
                                $("#modalDeleteSMTPServer").dialog('destroy');
                                $("#modalDeleteSMTPServer").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteSMTPServer").dialog('destroy');
                        $("#modalDeleteSMTPServer").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteSMTPServer").dialog('destroy');
                $("#modalDeleteSMTPServer").empty();
            }
        };

        $("#modalDeleteSMTPServer").load("/general-settings/delete-smtp-server-confirm/" + smtpId, [], function () {
            $("#modalDeleteSMTPServer").dialog(options);
            $("#modalDeleteSMTPServer").dialog("open");
        });
    });
})
