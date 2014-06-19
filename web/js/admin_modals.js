$('document').ready(function () {

    $("#btnDeleteClient").on('click', function (event) {
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
            title: 'Confirm Delete Client',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        var clientId = selected_rows[0];
                        $.ajax({
                            type: "POST",
                            url: '/administration/client/delete',
                            data: {
                                id: clientId
                            },
                            success: function (response) {
                                $("#modalDeleteClient").dialog('destroy');
                                $("#modalDeleteClient").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteClient").dialog('destroy');
                        $("#modalDeleteClient").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteClient").dialog('destroy');
                $("#modalDeleteClient").empty();
            }
        };

        var message = 'Are you sure you want to delete this client?<br />All related information will also be deleted (projects, users, issues, etc)';
        $("#modalDeleteClient").html(message);
        $("#modalDeleteClient").dialog(options);
        $("#modalDeleteClient").dialog('open');
    });
});