$('document').ready(function () {

    $(document).on('click', "#btnCreateNotebook", function (event) {
        event.preventDefault();

        $('#contentMenuNotebooks').hide();

        var options = {
            title: 'Create Notebook',
            buttons: [
                {
                    text: "Create Notebook",
                    click: function () {
                        var notebookName = $('#notebook_name').val().trim();

                        if (notebookName == '') {
                            $('#errorNotebookName').html('The notebook name can not be empty');
                            return;
                        }
                        $.ajax({
                            type: "POST",
                            url: '/quick-notes/notebook/add',
                            data: {
                                name: $('#notebook_name').val(),
                                description: $('#notebook_description').val()
                            },
                            success: function (response) {
                                $("#modalCreateNotebook").dialog('destroy');
                                $("#modalCreateNotebook").empty();
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
                $("#modalCreateNotebook").dialog('destroy');
                $("#modalCreateNotebook").empty();
            }
        };

        $("#modalCreateNotebook").load("/quick-notes/notebook/add/dialog", [], function () {
            $("#modalCreateNotebook").dialog(options);
            $("#modalCreateNotebook").dialog("open");
        });
    });

    $(document).on('click', "#btnCreateTag", function (event) {
        event.preventDefault();

        $('#contentMenuNotebooks').hide();
        $('#menuNotebooks').css('background-color', '#6A8EB2');
        var options = {
            title: 'Create Tag',
            buttons: [
                {
                    text: "Create Tag",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/quick-notes/tag/add',
                            data: {
                                name: $('#tag_name').val(),
                                description: $('#tag_description').val()
                            },
                            success: function (response) {
                                if ('1' == response) {
                                    $("#modalCreateTag").dialog('destroy');
                                    $("#modalCreateTag").empty();
                                    location.reload();
                                } else {
                                    // duplicate
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
                $("#modalCreateTag").dialog('destroy');
                $("#modalCreateTag").empty();
            }
        };

        $("#modalCreateTag").load("/quick-notes/tag/add/dialog", [], function () {
            $("#modalCreateTag").dialog(options);
            $("#modalCreateTag").dialog("open");
        });
    });

    $(document).on('click', "#btnDeleteNote", function (event) {
        event.preventDefault();

        var noteId = $('#note_id').val();
        var options = {
            title: 'Delete Note',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/quick-notes/note/delete',
                            data: {
                                id: noteId,
                                notebook_selected_id: $('#notebook_id').val()
                            },
                            success: function (response) {
                                $("#modalDeleteNote").dialog('destroy');
                                $("#modalDeleteNote").empty();

                                window.location.href = '/quick-notes/note/' + response;
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
                $("#modalDeleteNote").dialog('destroy');
                $("#modalDeleteNote").empty();
            }
        };

        $("#modalDeleteNote").load("/quick-notes/note/delete/dialog/" + noteId, [], function () {
            $("#modalDeleteNote").dialog(options);
            $("#modalDeleteNote").dialog("open");
        });
    });

    $(document).on('click', "#btnDeleteTag", function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return;
        }

        var tagId = selected_rows[0];

        var options = {
            title: 'Delete Tag',
            buttons: [
                {
                    text: "Delete Tag",
                    click: function () {
                        $.ajax({
                            type: "POST",
                            url: '/quick-notes/tag/delete',
                            data: {
                                id: tagId
                            },
                            success: function (response) {
                                $("#modalDeleteTag").dialog('destroy');
                                $("#modalDeleteTag").empty();

                                window.location.href = '/quick-notes/tag/all';
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
                $("#modalDeleteTag").dialog('destroy');
                $("#modalDeleteTag").empty();
            }
        };

        $("#modalDeleteTag").load("/quick-notes/tag/delete/dialog/" + tagId, [], function () {
            $("#modalDeleteTag").dialog(options);
            $("#modalDeleteTag").dialog("open");
        });
    });

    $("#btnDeleteNotebook").on('click', function (event) {
        event.preventDefault();

        if (selected_rows.length != 1) {
            return;
        }

        var notebookId = selected_rows[0];

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
            title: 'Delete Notebook',
            buttons: [
                {
                    text: "Delete",
                    click: function () {

                        $.ajax({
                            type: "POST",
                            url: '/quick-notes/notebook/delete',
                            data: {
                                id: notebookId
                            },
                            success: function (response) {
                                $("#modalDeleteNotebook").dialog('destroy');
                                $("#modalDeleteNotebook").empty();
                                location.reload();
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#modalDeleteNotebook").dialog('destroy');
                        $("#modalDeleteNotebook").empty();
                    }
                }
            ],
            close: function () {
                $("#modalDeleteNotebook").dialog('destroy');
                $("#modalDeleteNotebook").empty();
            }
        };

        $("#modalDeleteNotebook").load("/quick-notes/dialog/notebook/delete/" + notebookId, [], function () {
            $("#modalDeleteNotebook").dialog(options);
            $("#modalDeleteNotebook").dialog("open");
        });
    });
});