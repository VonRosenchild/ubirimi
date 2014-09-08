$(function () {
    /**************************************************
     * Context-Menu with Sub-Menu
     **************************************************/
    $.contextMenu({
        selector: '.menu_img',
        autoHide: true,
        zIndex: 3000,
        callback: function (key, options) {
            if (key == 'delete_step') {
                var answer = confirm('Are you sure you want to delete this step?')
            }
        },
        items: {
            "edit": {"name": "Edit"},
            "delete_step": {"name": "Delete Step"}

        }
    });

    $.contextMenu({
        selector: '.settings_transition',
        autoHide: true,
        zIndex: 3000,
        callback: function (key, options) {
            var workflow_id = $('#workflow_id').val();
            var id_label_transition = options.$trigger.attr("id");

            var id = $(this).attr("id").replace('label_transition_', '');
            var ids = id.split('_');

            var id_from = ids[0];
            var id_to = ids[1];

            if (key == 'screen_transition') {

                var options = {


                    dialogClass: "ubirimi-dialog",






                    title: 'Set transition settings',
                    buttons: [
                        {
                            text: "Set",
                            click: function () {
                                var conns = jsPlumb.getConnections({source: 'node_status_' + id_from, target: 'node_status_' + id_to});
                                var connection = conns[0];
                                connection.getOverlay("label").setLabel($('#transition_name_modal').val() + '<img id="label_transition_' + id_from + '_' + id_to + '" class="settings_transition" width="16px" src="/img/settings.png" />');
                                $.ajax({
                                    type: "POST",
                                    url: '/yongo/administration/ajax/workflow/update-transition-data',
                                    data: {
                                        transition_name: $('#transition_name_modal').val(),
                                        workflow_id: workflow_id,
                                        screen_id: $('#screens_modal option:selected').val(),
                                        id_from: id_from,
                                        id_to: id_to
                                    },
                                    success: function (response) {

                                    }
                                });

                                $(this).dialog("close");
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
                        $("#setTransitionScreen").dialog('destroy');
                        $("#setTransitionScreen").empty();
                    }
                };
                $("#setTransitionScreen").load("/yongo/administration/ajax/workflow/render-transition-screen/" + id_from + '/' + id_to + '/' + workflow_id, [], function () {
                    $("#setTransitionScreen").dialog(options);
                    $("#setTransitionScreen").dialog("open");
                });
            } else if (key == 'delete_transition') {
                var firstTransitionData = $('#first_transition').val();

                if (firstTransitionData ==(id_from + '_' + id_to)) {
                    alert('You can not delete the initial transition.');
                } else {
                    var answer = confirm('Are you sure you want to delete this transition?');
                    if (answer) {
                        var conn = jsPlumb.getConnections({source: 'node_status_' + id_from, target:'node_status_' + id_to})[0];
                        jsPlumb.detach(conn);

                        // delete from database
                        $.ajax({
                            type: "POST",
                            url: '/yongo/administration/ajax/workflow/delete-transition',
                            data: {
                                workflow_id: workflow_id,
                                id_from: id_from,
                                id_to: id_to
                            },
                            success: function (response) {

                            }
                        });
                    }
                }
            } else if (key == 'edit_post_functions') {

                var options = {


                    dialogClass: "ubirimi-dialog",






                    title: 'Done',
                    buttons: [
                        {
                            text: "Save post function",
                            click: function () {
                                // get all the function fields
                                var fields = $("[id^='post_function_']");
                                var field_ids = [];
                                var field_values = [];
                                fields.each(function() {
                                    field_ids.push(this.id);
                                    field_values.push($('#' + this.id).val());
                                });
                                $.ajax({
                                    type: "POST",
                                    url: '/yongo/administration/ajax/workflow/save-transition-post-function',
                                    data: {
                                        field_ids: field_ids,
                                        field_values: field_values,
                                        workflow_id: workflow_id,
                                        id_from: id_from,
                                        id_to: id_to
                                    },
                                    success: function (response) {

                                    }
                                });
                                $(this).dialog("close");
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
                        $("#setTransitionPostFunction").dialog('destroy');
                        $("#setTransitionPostFunction").empty();
                    }
                };
                $("#setTransitionPostFunction").load("/yongo/administration/ajax/workflow/render-transition-post-function/" + id_from + '/' + id_to + '/' + workflow_id, [], function () {
                    $("#setTransitionPostFunction").dialog(options);
                    $("#setTransitionPostFunction").dialog("open");
                });
            }
        },
        items: {
            "screen_transition": {"name": "Transition settings&nbsp;&nbsp;"},
            "delete_transition": {"name": "Delete"},
            "edit_post_functions": {"name": "Edit post functions"}
        }
    });

    $(document).on('change', '#post_function_select', function (event) {
        event.preventDefault();
        var function_id = $("#post_function_select").val();
        if (function_id != -1) {
            $.ajax({
                type: "POST",
                url: '/yongo/administration/ajax/workflow/render-post-function',
                data: {
                    function_id: function_id
                },
                success: function (response) {
                    $('#content_post_function').html(response);
                }
            });
        } else {
            $('#content_post_function').html('');
        }
    });
});