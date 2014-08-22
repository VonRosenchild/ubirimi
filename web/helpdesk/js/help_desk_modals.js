$('document').ready(function () {

    $('#btnDeleteSLA').on('click', function (event) {
        event.preventDefault();
        var slaId = $('#sla_id').val();

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
            title: 'Delete SLA',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/helpdesk/sla/delete',
                            data: {
                                id: slaId,
                                project_id: $('#project_id').val()
                            },
                            success: function (response) {
                                $("#modalDeleteSLA").dialog('destroy');
                                $("#modalDeleteSLA").empty();

                                window.location.href = response;
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
                $("#modalDeleteSLA").dialog('destroy');
                $("#modalDeleteSLA").empty();
            }
        };

        $("#modalDeleteSLA").load("/helpdesk/sla/dialog/delete/" + slaId, [], function () {
            $("#modalDeleteSLA").dialog(options);
            $("#modalDeleteSLA").dialog("open");
        });
    });

    $('#btnDeleteQueue').on('click', function (event) {
        event.preventDefault();
        var queueId = $('#queue_id').val();

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
            title: 'Delete Queue',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/helpdesk/queue/delete',
                            data: {
                                id: queueId,
                                project_id: $('#project_id').val()
                            },
                            success: function (response) {
                                $("#modalDeleteQueue").dialog('destroy');
                                $("#modalDeleteQueue").empty();

                                window.location.href = response;
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
                $("#modalDeleteQueue").dialog('destroy');
                $("#modalDeleteQueue").empty();
            }
        };

        $("#modalDeleteQueue").load("/helpdesk/queue/dialog/delete/" + queueId, [], function () {
            $("#modalDeleteQueue").dialog(options);
            $("#modalDeleteQueue").dialog("open");
        });
    });

    $('#btnDeleteSLACalendar').on('click', function (event) {
        event.preventDefault();
        var calendarId = selected_rows[0];

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
            title: 'Delete SLA Calendar',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/helpdesk/sla/calendar/delete',
                            data: {
                                id: calendarId
                            },
                            success: function (response) {
                                $("#modalDeleteSLACalendar").dialog('destroy');
                                $("#modalDeleteSLACalendar").empty();

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
                $("#modalDeleteSLACalendar").dialog('destroy');
                $("#modalDeleteSLACalendar").empty();
            }
        };

        var delete_possible = $('#delete_possible_' + calendarId).val();
        if (delete_possible == 0) {
            options.buttons.shift();
        }

        $("#modalDeleteSLACalendar").load("/helpdesk/sla/calendar/delete/dialog/" + calendarId, [], function () {
            $("#modalDeleteSLACalendar").dialog(options);
            $("#modalDeleteSLACalendar").dialog("open");
        });
    });

    $('#btnDeleteOrganization').on('click', function (event) {
        event.preventDefault();
        var organizationId = selected_rows[0];

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
            title: 'Delete Organization',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/helpdesk/administration/organizations/delete',
                            data: {
                                id: organizationId
                            },
                            success: function (response) {
                                $("#modalDeleteOrganization").dialog('destroy');
                                $("#modalDeleteOrganization").empty();

                                window.location.reload();
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
                $("#modalDeleteOrganization").dialog('destroy');
                $("#modalDeleteOrganization").empty();
            }
        };

        $("#modalDeleteOrganization").load("/helpdesk/administration/organizations/dialog/delete/" + organizationId, [], function () {
            $("#modalDeleteOrganization").dialog(options);
            $("#modalDeleteOrganization").dialog("open");
        });
    });
});