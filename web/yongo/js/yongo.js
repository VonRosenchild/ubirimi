var outgoingTransitions = null;

function loadIssueAgileContext(issueId) {

    if (issueId) {
        $.ajax({
            type: "POST",
            url: '/agile/render-issue',
            data: {
                id: issueId
            },
            success: function (response) {
                $('#agileIssueContent').html(response);
            }
        });
    } else {
        var htmlContent = '<div class="headerPageText">Plan Mode</div>';
        htmlContent += '<div>Plan mode is where you scrub your backlog: review, estimate, and prioritise your stories and bugs, then create and plan sprints (iterations of work).</div>';

        $('#agileIssueContent').html(htmlContent);
    }
}

function loadIssueComments() {
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

function loadIssueWorkLog() {
    var issue_id = $('#issue_id').val();
    var projectId = $('#project_id').val();
    $.ajax({
        type: "POST",
        url: '/yongo/issue-work-log',
        data: {
            issue_id: issue_id,
            project_id: projectId
        },
        success: function (response) {
            $('#tabContent').html(response);
        }
    });
}

function loadIssueHistory() {
    var issue_id = $('#issue_id').val();
    $.ajax({
        type: "POST",
        url: '/yongo/show-history',
        data: {
            issue_id: issue_id
        },
        success: function (response) {
            $('#tabContent').html(response);
        }
    });
}

function updateIssueAgileElementMetaData(param) {
    var element = param[0];
    var stepIdFrom = param[1];
    var columnId = param[2];
    var indexSection = param[3];
    var parentId = param[4];
    var issueId = param[5];

    if (parentId == -1) {
        window.location.reload();
    } else {
        // check if all the children are moved to done. If yes present options for the parent issue regarding the transitions possible
        $.ajax({
            type: "POST",
            url: '/agile/check-issue-parent-completed-subtasks',
            data: {
                id: parentId,
                statuses: $('#last_column_statuses').val()
            },
            success: function (response) {

                if (response != 'no') {

                    var options = {
                        dialogClass: "ubirimi-dialog",
                        title: 'Update Parent Issue',
                        buttons: [
                            {
                                text: 'Update',
                                click: function () {

                                    $("#agileModalUpdateParent").dialog('close');
                                    var transition_data = $('input[name=transitions_for_parent_issue]:checked', '#parent_issue_possible_transitions').attr('id');

                                    var useScreen = parseInt(transition_data.replace('parent_possible_transition_', '').split('_')[0]);
                                    var stepIdFrom = transition_data.replace('parent_possible_transition_', '').split('_')[1];
                                    var stepIdTo = transition_data.replace('parent_possible_transition_', '').split('_')[2];
                                    var workflowId = transition_data.replace('parent_possible_transition_', '').split('_')[3];
                                    var projectId = transition_data.replace('parent_possible_transition_', '').split('_')[4];
                                    var transitionName = $('input[name=transitions_for_parent_issue]:checked', '#parent_issue_possible_transitions').val();

                                    if (useScreen) {

                                        function reloadScreen() {
                                            window.location.reload();
                                        }

                                        var options = {
                                            dialogClass: "ubirimi-dialog",
                                            title: transitionName,
                                            buttons: [
                                                {
                                                    text: transitionName,
                                                    click: function () {
                                                        doTransitionWithScreen(parentId, stepIdFrom, stepIdTo, workflowId, 'agileModalTransitionWithScreen', reloadScreen);
                                                    }
                                                },
                                                {
                                                    text: "Cancel",
                                                    click: function () {
                                                        window.location.reload();
                                                    }
                                                }
                                            ],
                                            close: function () {
                                                $("#agileModalTransitionWithScreen").dialog('close');
                                            }
                                        };

                                        $("#agileModalTransitionWithScreen").load("/agile/render-transition-issue/" + workflowId + '/' + projectId + '/' + stepIdFrom + '/' + stepIdTo + '/' + issueId, [], function () {
                                            $("#agileModalTransitionWithScreen").dialog(options);
                                            $("#agileModalTransitionWithScreen").dialog("open");

                                            // call initialization file
                                            if (window.File && window.FileList && window.FileReader) {
                                                initializaFileUpload(parentId);
                                            }
                                        });

                                    } else {
                                        doTransitionWithoutScreen(parentId, stepIdFrom, stepIdTo, workflowId, function () {
                                            location.reload()
                                        });
                                    }
                                }
                            },
                            {
                                text: "Cancel",
                                click: function () {
                                    $("#agileModalUpdateParent").dialog('close');
                                }
                            }
                        ],
                        close: function () {
                            $("#agileModalUpdateParent").dialog('close');
                        }
                    };

                    $("#agileModalUpdateParent").load("/agile/complete-parent-issue-dialog", {data: response}, function () {
                        $("#agileModalUpdateParent").dialog(options);
                        $("#agileModalUpdateParent").dialog("open");
                    });
                } else {
                    window.location.reload();
                }
            }
        });
    }
}

function doTransitionWithoutScreen(issueId, stepIdFrom, stepIdTo, workflowId, functionToCall, functionToCallParameters) {
    $.ajax({
        type: "POST",
        url: '/yongo/issue/save-issue-transition-quick',
        data: {
            issue_id: issueId,
            step_id_from: stepIdFrom,
            step_id_to: stepIdTo,
            workflow_id: workflowId
        },
        success: function (response) {
            if (functionToCall)
                functionToCall(functionToCallParameters);
        }
    });
}

function doTransitionWithScreen(issueId, stepIdFrom, stepIdTo, workflowId, modalIdToClose, functionToCall, functionToCallParameters) {

    var final_step_id = $('#final_step_id').val();
    var fields = $("[id^='field_type_']");
    var fieldsCustom = $("[id^='field_custom_type_']");
    var attachments = $("[id^='attach_']");

    var field_types = [];
    var field_values = [];
    var field_types_custom = [];
    var field_values_custom = [];

    for (var i = 0; i < fields.length; i++) {
        field_types[i] = fields[i].getAttribute('id').replace('field_type_', '');
        field_values[i] = $('#' + fields[i].getAttribute('id')).val();
    }
    var attach_ids = [];
    for (var i = 0; i < attachments.length; i++) {
        var check_id = attachments[i].getAttribute('id');
        var checked = ($('#' + check_id).is(':checked'));
        if (checked)
            attach_ids.push(attachments[i].getAttribute('id').replace('attach_', ''));
    }

    // deal with the custom fields
    for (var i = 0; i < fieldsCustom.length; i++) {
        var elemId = fieldsCustom[i].getAttribute('id');
        if (elemId.indexOf('chzn') == -1) {
            field_types_custom.push(elemId.replace('field_custom_type_', ''));
            field_values_custom.push($('#' + elemId).val());
        }
    }

    $.ajax({
        type: "POST",
        url: '/yongo/issue/save-issue-transition',
        data: {
            issue_id: issueId,
            field_types: field_types,
            field_values: field_values,
            step_id_from: stepIdFrom,
            step_id_to: stepIdTo,
            workflow_id: workflowId,
            attach_ids: attach_ids,
            field_types_custom: field_types_custom,
            field_values_custom: field_values_custom
        },
        success: function (response) {
            $("#" + modalIdToClose).dialog('destroy');
            $("#" + modalIdToClose).empty();

            if (functionToCall)
                functionToCall(functionToCallParameters)
        }
    });
}

$('document').ready(function () {

    $(".select2Input").select2();
    $(".select2InputSmall").select2();
    $(".select2InputMedium").select2();

    $(function () {
        $("#sortable").sortable({

            containment: "#containerColumns",
            tolerance: "pointer",
            cancel: ".notSortable",
            start: function (e, ui) {
                ui.placeholder.height(ui.item.height());
                ui.placeholder.width(ui.item.width());
                ui.placeholder.css("visibility", "visible");
                ui.placeholder.css("background-color", "#CCCCCC");
            },
            cursor: 'move',

            stop: function (event, ui) {

                var columnIds = [];

                $("[id^='agile_move_column_']").each(function () {
                    var id = this.id.replace('agile_move_column_', '');
                    columnIds.push(id);
                });

                $.ajax({
                    type: "POST",
                    url: '/agile/update-column-position',
                    data: {
                        order: columnIds
                    },
                    success: function (response) {

                    }
                });
            }
        }).disableSelection();

        $(".draggableColumn").draggable({
            containment: "#agile_columns_id:not(:first-child)",
            cancel: '.notSortable',
            connectToSortable: "#sortable",
            scroll: false,
            zIndex: 111,
            handle: '.handleColumn',
            cursor: 'move'
        });

        $(".draggable").draggable({
            containment: "#agile_columns",
            scroll: false,
            zIndex: 100
        });

        $(".droppable").droppable({
            hoverClass: "drop_hover",
            accept: ".draggable",
            drop: function (event, ui) {
                var elem = ui.draggable;
                elem.css('left', '');
                elem.css('top', '');
                $(this).append(elem);
                $.ajax({
                    type: "POST",
                    url: '/agile/update-column-data',
                    data: {
                        status_id: elem.attr('id').replace('status_', ''),
                        new_column_id: $(this).attr('id').replace('column_', ''),
                        board_id: $('#board_id').val()
                    },
                    success: function (response) {

                    }
                });
            }
        });

        var max_index_section = parseInt($('#max_index_section').val());

        for (var i = 1; i <= max_index_section; i++) {
            $(".draggableIssueAgile_" + i).draggable({
                containment: ".agile_work_" + i,
                scroll: false,
                zIndex: 2000,
                revert: true,
                revertDuration: 1,

                drag: function (event, ui) {

                    $('.ui-draggable-dragging').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
                    $("#temporary_statuses > div").each(function () {
                        var cursorX = event.pageX;
                        var cursorY = event.pageY;

                        var statusElement = $('#' + this.id);
                        var idElementStatus = statusElement[0].getAttribute('id');
                        var status_width = statusElement.width();
                        var status_height = statusElement.height();
                        var position_status = statusElement.offset();
                        var status_left = position_status.left;
                        var status_top = position_status.top;

                        if (cursorX > status_left && cursorX < (status_left + status_width) && cursorY > status_top && cursorY < (status_top + status_height)) {
                            $('#' + idElementStatus).css('background-color', '#CCEBD6');
                        } else {
                            $('#' + idElementStatus).css('background-color', '#C2EBFF');
                        }
                    })
                },

                start: function (event, ui) {
                    var idArray = ui.helper.attr("id").replace('issue_in_column_', '').split('_');
                    var issueId = idArray[1];
                    var projectId = idArray[2];
                    var stepIdFrom = idArray[3];
                    var workflowId = idArray[4];

                    $.ajax({
                        type: "POST",
                        url: '/agile/get-transitions-for-issue',
                        data: {
                            issue_id: issueId,
                            project_id: projectId,
                            step_id_from: stepIdFrom,
                            workflow_id: workflowId
                        },
                        success: function (response) {
                            outgoingTransitions = jQuery.parseJSON(response);
                        }
                    });
                },
                stop: function (event, ui) {
                    var columnId = ui.helper.attr("id").replace('issue_in_column_', '').split('_')[0];
                    // do some cleanup
                    $('.status_for_column_' + i + '_' + columnId).each(function () {
                        var element = $('#' + this.id);
                        element.data('hidden_element', false);
                        element.show();
                    });

                    $('.ui-draggable-dragging').css('box-shadow', '');
                    $('#temporary_statuses').remove();
                }
            });
        }

        for (var i = 1; i <= max_index_section; i++) {
            $(".droppableAgileColumn_" + i).droppable({
                accept: ".draggableIssueAgile_" + i,
                tolerance: "pointer",

                drop: function (event, ui) {

                    // we must determine the status the issue was dropped on
                    $("#temporary_statuses > div").each(function () {
                        var cursorX = event.pageX;
                        var cursorY = event.pageY;

                        var elementStatus = $('#' + this.id);

                        var columnId = this.id.replace('status_for_column_', '').split('_')[0];
                        var status_width = elementStatus.width();
                        var status_height = elementStatus.height();
                        var position_status = elementStatus.offset();
                        var status_left = position_status.left;
                        var status_top = position_status.top;

                        if (cursorX > status_left && cursorX < (status_left + status_width) && cursorY > status_top && cursorY < (status_top + status_height)) {
                            var issueDraggableElement = ui.draggable;
                            var idArray = issueDraggableElement.attr("id").replace('issue_in_column_', '').split('_');

                            var issueId = idArray[1];
                            var projectId = idArray[2];
                            var stepIdFrom = idArray[3];
                            var workflowId = idArray[4];
                            var indexSection = idArray[5];
                            var parentId = idArray[6];

                            var finalStatusId = this.id.replace('status_for_column_', '').split('_')[1];

                            // get the step TO id
                            $.ajax({
                                type: "POST",
                                url: '/agile/get-step-by-workflow-status',
                                data: {
                                    workflow_id: workflowId,
                                    status_id: finalStatusId
                                },
                                success: function (response) {
                                    var step = jQuery.parseJSON(response);
                                    var stepIdTo = step.id;
                                    $('#temporary_statuses').remove();

                                    var issueDraggedElement = ui.draggable;
                                    issueDraggedElement.hide();

                                    $.ajax({
                                        type: "POST",
                                        url: '/agile/get-transition-by-step-from-to',
                                        data: {
                                            workflow_id: workflowId,
                                            step_id_from: stepIdFrom,
                                            step_id_to: stepIdTo
                                        },
                                        success: function (response) {
                                            var obj = jQuery.parseJSON(response);
                                            var transitionName = obj.transition_name;

                                            if (obj.screen_id) {
                                                var options = {
                                                    dialogClass: "ubirimi-dialog",
                                                    title: transitionName,
                                                    buttons: [
                                                        {
                                                            text: transitionName,
                                                            click: function () {
                                                                doTransitionWithScreen(issueId, stepIdFrom, stepIdTo, workflowId, 'agileModalTransitionWithScreen', updateIssueAgileElementMetaData, [issueDraggableElement, stepIdTo, columnId, indexSection, parentId, issueId]);
                                                            }
                                                        },
                                                        {
                                                            text: "Cancel",
                                                            click: function () {
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: '/yongo/issue/cleanup',
                                                                    data: {
                                                                        issue_id: issueId
                                                                    },
                                                                    success: function (response) {
                                                                        $("#agileModalTransitionWithScreen").dialog('close');

                                                                        $(issueDraggedElement).show();
                                                                        $(issueDraggedElement).animate($(issueDraggedElement).data('startPosition'), 500);
                                                                    }
                                                                });
                                                            }
                                                        }
                                                    ],
                                                    close: function () {
                                                        $("#agileModalTransitionWithScreen").dialog('close');
                                                    }
                                                };

                                                $("#agileModalTransitionWithScreen").load("/agile/render-transition-issue/" + workflowId + '/' + projectId + '/' + stepIdFrom + '/' + stepIdTo + '/' + issueId, [], function () {
                                                    $("#agileModalTransitionWithScreen").dialog(options);
                                                    $("#agileModalTransitionWithScreen").dialog("open");

                                                    // call initialization file
                                                    if (window.File && window.FileList && window.FileReader) {
                                                        initializaFileUpload(issueId);
                                                    }
                                                });
                                            } else {
                                                doTransitionWithoutScreen(issueId, stepIdFrom, stepIdTo, workflowId, updateIssueAgileElementMetaData, [issueDraggableElement, stepIdTo, columnId, indexSection, parentId, issueId]);
                                            }
                                        }
                                    });
                                }
                            });

                            return false;
                        }
                    })
                },
                over: function (event, ui) {

                    if (!outgoingTransitions) {
                        return;
                    }
                    var draggedElementColumnId = ui.draggable.attr("id").replace('issue_in_column_', '').split('_')[0];
                    var indexSection = ui.draggable.attr("id").replace('issue_in_column_', '').split('_')[5];

                    var columnId = this.id.replace('column_data_', '').split('_')[0];
                    if (draggedElementColumnId == columnId) {
                        return
                    }

                    var dropColumnId = '#column_data_' + columnId + '_' + indexSection;
                    var statusesCount = $(dropColumnId).find('.status_for_column_' + indexSection + '_' + columnId).length;

                    var hiddenStatusesCount = 0;
                    $(dropColumnId).find('.status_for_column_' + indexSection + '_' + columnId).each(function () {
                        var columnStatusId = this.id.replace('status_for_column_', '').split('_')[1];
                        var found = false;
                        $.each(outgoingTransitions, function (key, value) {
                            if (columnStatusId == value.linked_issue_status_id) {
                                found = true;
                            }
                        });
                        if (found) {
                            $('#' + this.id).data('hidden_element', false);
                        } else {
                            $('#' + this.id).hide();
                            $('#' + this.id).data('hidden_element', true);
                            hiddenStatusesCount++;
                        }
                    });

                    if (hiddenStatusesCount == statusesCount) {
                        return
                    }

                    $(dropColumnId).find('.status_for_column_' + indexSection + '_' + columnId).each(function () {
                        if (!$('#' + this.id).data('hidden_element')) {
                            var heightDropColumn = $(dropColumnId).height();

                            if (heightDropColumn > $(window).height()) {
                                heightDropColumn = $(window).height() - 215;
                            }

                            $('#' + this.id).css('position', 'relative');
                            $('#' + this.id).css('top', $('#agile_wrapper_work').scrollTop());
                            $('#' + this.id).show();
                        }
                    });

                    var htmlForDiv = $('#statuses_for_column_' + columnId + '_' + indexSection).html();

                    var div = $('<div id="temporary_statuses" style="height: 50px;">').html(htmlForDiv);
                    var columnElement = $(dropColumnId);

                    var leftOffset = 0;

                    /*Browser detection patch*/
                    jQuery.browser = {};
                    jQuery.browser.mozilla = /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase());
                    jQuery.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
                    jQuery.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
                    jQuery.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());

                    var widthAdjustment = 0;
                    if (jQuery.browser.webkit)
                        leftOffset = 5;
                    else if ((jQuery.browser.mozilla)) {
                        leftOffset = 1;
                        widthAdjustment = 1;
                    } else if ((jQuery.browser.opera))
                        leftOffset = 5;

                    $(div).css('left', columnElement.position().left + leftOffset);

                    var offset = columnElement.offset();
                    var newTop = offset.top - $('#agile_wrapper_work').scrollTop() + 4;

                    $(div).css('top', newTop);
                    $(div).css('width', columnElement.width() + widthAdjustment);

                    $(div).css('opacity', 1);
                    $(div).css('position', 'absolute');
                    $(div).css('z-index', '1000');

                    var newChildrenHeight = (getVisibleHeightOfElement(columnElement.parent()) - 26) / (statusesCount - hiddenStatusesCount);

                    $(div).children().each(function (i) {
                        $(this).css('height', newChildrenHeight);
                    });

                    $(div).prependTo(dropColumnId);
                },
                out: function (event, ui) {

                    $('#temporary_statuses').remove();
                }
            });
        }
    });

    // in case there are custom date fields set them as date pickers
    $("[id^='custom_date_field_']").each(function () {
        $('#' + this.id).datepicker({dateFormat: "yy-mm-dd"});
    });

    $('#add_user').on('click', function () {
        $(location).attr('href', '/yongo/administration/add-user');
    });

    if ($('#project_activity_stream').is(':visible')) {
        $.ajax({
            type: "POST",
            url: '/yongo/project/activity-stream',
            data: {
                id: $('#project_id').val()
            },
            success: function (response) {
                $('#project_activity_stream').html(response);
            }
        });
    }

    $('#contentActivity').on('click', '#add_comment', function () {

        var comment = $('#comment').val();
        var id = $('#issue_id').val();

        if (0 == comment.length) {
            return false;
        }

        $.ajax({
            type: "POST",
            url: '/yongo/comment/add',
            data: {
                id: id,
                content: comment
            },
            success: function (response) {
                comment = $('#comment').val('');

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
    });

    function loadUserHistory() {
        var user_id = $('#user_id').val();
        $.ajax({
            type: "POST",
            url: '/yongo/show-history',
            data: {
                user_id: user_id
            },
            success: function (response) {
                $('#u_tabContent').html(response);
            }
        });
    }

    function loadProjectHistory() {
        var project_id = $('#project_id').val();
        $.ajax({
            type: "POST",
            url: '/yongo/show-history',
            data: {
                project_id: project_id
            },
            success: function (response) {
                $('#p_tabContent').html(response);
            }
        });
    }

    $("[id^='add_workflow_condition']").on('click', function (event) {
        event.preventDefault();

        var type = $(this).attr("id").replace('add_workflow_condition_', '');
        var transitionId = $('#transition_id').val();
        $.ajax({
            type: "POST",
            url: '/yongo/administration/workflow/add-condition-string',
            data: {
                transition_id: transitionId,
                type: type
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $('#p_tab_history').on('click', function (event) {
        event.preventDefault();
        $(this).parent().children().filter('.active').removeClass('active');
        $(this).addClass('active');
        $('#p_tabContent').html('');

        loadProjectHistory();
    });

    $('#u_tab_history').on('click', function (event) {
        event.preventDefault();
        $(this).parent().children().filter('.active').removeClass('active');
        $(this).addClass('active');
        $('#u_tabContent').html('');

        loadUserHistory();
    });

    $('#is_tab_history').on('click', function (event) {
        event.preventDefault();
        $(this).parent().children().filter('.active').removeClass('active');
        $(this).addClass('active');
        $('#tabContent').html('');

        loadIssueHistory();
    });

    $('#is_tab_work_log').on('click', function (event) {
        event.preventDefault();
        $(this).parent().children().filter('.active').removeClass('active');
        $(this).addClass('active');
        $('#tabContent').html('');

        loadIssueWorkLog();
    });

    $('#is_tab_comment').on('click', function (event) {
        event.preventDefault();
        $(this).parent().children().filter('.active').removeClass('active');
        $(this).addClass('active');
        $('#tabContent').html('');
        loadIssueComments();
    });

    var tab_comments = $('#is_tab_comment');

    if (tab_comments.html())
        loadIssueComments();

    function deleteIssue() {

        var options = {
            dialogClass: "ubirimi-dialog",
            title: 'Delete Issue Confirmation',
            buttons: [
                {
                    text: "Delete",
                    click: function () {
                        var issue_id = selected_rows[0];

                        var redirect = false;
                        if (!issue_id) {
                            issue_id = $('#issue_id').val();
                            if (issue_id)
                                redirect = true;
                        }

                        $.ajax({
                            type: "POST",
                            url: '/yongo/issue/delete',
                            data: {
                                issue_id: issue_id
                            },
                            success: function (response) {
                                $("#deleteIssueModal").dialog('destroy');
                                $("#deleteIssueModal").empty();

                                var obj = jQuery.parseJSON(response);
                                var link = '';
                                if (obj.go_to_search) {
                                    document.location.href = '/yongo/issue/search?' + obj.url; // search
                                } else if (obj.go_to_dashboard) {
                                    document.location.href = '/yongo/my-dashboard'; // search
                                }
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    click: function () {
                        $("#deleteIssueModal").dialog('destroy');
                        $("#deleteIssueModal").empty();
                    }
                }
            ],
            close: function () {
                $("#deleteIssueModal").dialog('destroy');
                $("#deleteIssueModal").empty();
            }
        };

        $("#deleteIssueModal").html('Are you sure you want to delete this issue?<br />All the information linked to it will be also deleted.');
        $("#deleteIssueModal").dialog(options);
        $("#deleteIssueModal").dialog('open');
    }

    $('#btnDeleteIssueDetail').on('click', function (event) {

        event.preventDefault();

        deleteIssue();
    });

    $('#filesToUpload').on('change', function () {

        var input = document.getElementById("filesToUpload");
        var fileList = document.getElementById("fileList");
        while (fileList.hasChildNodes()) {
            fileList.removeChild(fileList.firstChild);
        }
        for (var i = 0; i < input.files.length; i++) {
            var div = document.createElement("div");
            div.innerHTML = '<input name="att_chk_' + i + '" type="checkbox" value="1" checked />' + input.files[i].name;
            fileList.appendChild(div);
        }
    });

    $(document).on('change', '#field_type_project', function (event) {
        event.preventDefault();
        var project_id = $("#field_type_project").val();

        $.ajax({
            type: "POST",
            url: '/yongo/project/get-project-data',
            data: {
                project_id_arr: [project_id]
            },
            success: function (response) {
                var obj = jQuery.parseJSON(response);

                $('#field_type_type').find('option').remove();
                $.each(obj.type_arr, function (key, value) {
                    $('#field_type_type').append($('<option></option>').val(value.id).html(value.name));
                });
                $('#field_type_type').select2('val', $("#field_type_type option:first").val());

                $.ajax({
                    type: "POST",
                    url: '/yongo/issue/render-field-list',
                    data: {
                        issue_type_id: $('#field_type_type').val(),
                        project_id: project_id,
                        operation_id: $('#operation_id').val()
                    },
                    success: function (response) {
                        var fields = $("[id^='field_type_'], [id^='field_custom_type_']");
                        var fieldsData = retainFieldsData(fields);

                        $('#tableFieldList').find("tr:gt(2)").remove();
                        $('#tableFieldList').append(response);

                        // restore fields data
                        restoreFieldsData(fields, fieldsData);

                        $("select.select2Input").select2();
                        var due_date_picker = $("#field_type_due_date");
                        if (due_date_picker.length) {
                            due_date_picker.datepicker({dateFormat: "yy-mm-dd"});
                        }

                        if (window.File && window.FileList && window.FileReader) {
                            initializaFileUpload();
                        }
                    }
                });
            }
        });
    });

    $("#project_issue_settings").on('change', function (event) {
        event.preventDefault();
        var project_id = $("#project_issue_settings").val();

        var url = document.location.href;
        var parts = url.split('?');
        document.location.href = parts[0] + '?project_id=' + project_id;
    });

    $('#btnConfigureBoard').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/agile/configure-board/' + selected_rows[0];
    });

    $('#btnCopyWorkflow').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/workflow/copy/' + selected_rows[0];
    });

    $('#btnEditBoard').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/agile/board/edit/' + selected_rows[0];
    });

    $('#btnEditEvent').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/edit-event/' + selected_rows[0];
    });

    $('#btnEditLinkType').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/link-type/edit/' + selected_rows[0];
    });

    $('#btnEditIssueSecurityScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/issue-security-scheme/edit/' + selected_rows[0];
    });

    $('#btnEditIssueSecuritySchemeLevel').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/issue-security-scheme-level/edit/' + selected_rows[0];
    });

    $('#btnIssueSecurityLevels').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/issue-security-scheme-levels/' + selected_rows[0];
    });

    $('#btnCustomFieldPlaceOnScreens').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/custom-field/edit-field-screen/' + selected_rows[0];
    });

    $('#btnDeleteTransitions').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/workflow/delete-transitions/' + selected_rows[0];
    });

    $('#btnEditPostFunction').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/workflow/edit-post-function-data/' + selected_rows[0];
    });

    $('#btnEditStepProperty').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/workflow/edit-step-property/' + selected_rows[0];
    });

    $('#btnEditPage').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/documentador/page/edit/' + selected_rows[0];
    });

    $('#btnWorkflowViewAsText').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/workflow/view-as-text/' + selected_rows[0];
    });

    $('#btnEditWorkflowStep').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/workflow/edit-step/' + selected_rows[0] + '?source=workflow_text';
    });

    $('#btnNewTransitionForStep').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/workflow/add-transition/' + selected_rows[0];
    });

    $('#btnEditWorkflowScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/workflows/edit-scheme/' + selected_rows[0];
    });

    $('#btnCopyWorkflowScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/workflows/scheme/copy/' + selected_rows[0];
    });

    $('#btnNotifications').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/notification-scheme/edit/' + selected_rows[0];
    });

    $('#btnPermissions').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/permission-scheme/edit/' + selected_rows[0];
    });

    $('#btnEditFieldConfiguration').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/field-configuration/edit/' + selected_rows[0];
    });

    $('#btnEditProjectCategory').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/project/category/edit/' + selected_rows[0];
    });

    $('#btnEditOrganization').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/helpdesk/administration/organizations/edit/' + selected_rows[0];
    });

    $('#btnEditCustomer').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/helpdesk/administration/customers/edit/' + selected_rows[0];
    });

    $('#btnFieldConfigurationScreen').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1) {
            var field_configuration_id = $('#field_configuration').val();
            document.location.href = '/yongo/administration/field-configuration/edit-screen/' + field_configuration_id + '/' + selected_rows[0];
        }
    });

    $('#btnDesignWorkflow').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/workflow/design/' + selected_rows[0];
    });

    $('#btnEditIssueTypeScreenScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/screen/edit-scheme-issue-type/' + selected_rows[0];
    });

    $('#btnEditCustomFieldValue').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/custom-field/value/edit/' + selected_rows[0];
    });

    $('#btnEditNotificationScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/notification-scheme/edit-metadata/' + selected_rows[0];
    });

    $('#btnUserAssignInProjectRole').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/user/project-roles/' + selected_rows[0];
    });

    $('#btnEditPermissionScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/permission-scheme/edit-metadata/' + selected_rows[0];
    });

    $('#btnEditIssueTypeScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/issue/edit-type-scheme/' + selected_rows[0];
    });

    $('#btnCopyIssueTypeScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/issue-type-scheme/copy/' + selected_rows[0] + '?type=project';
    });

    $('#btnCopyWorkflowIssueTypeScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/issue-type-scheme/copy/' + selected_rows[0] + '?type=workflow';
    });

    $('#btnEditScreenSchemeMetadata').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/screen/edit-scheme/' + selected_rows[0];
    });

    $('#btnEditFieldConfigurationSchemeMetadata').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/field-configuration/scheme-metadata/edit/' + selected_rows[0];
    });

    $('#btnEditFieldConfigurationMetadata').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/field-configuration/metadata/edit/' + selected_rows[0];
    });

    $('#btnEditScreenSchemeData').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/screen/edit-scheme-data/' + selected_rows[0];
    });

    $('#btnEditIssueTypeScreenSchemeData').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/screen/edit-scheme-issue-type-data/' + selected_rows[0];
    });

    $('#btnEditFieldConfigurationSchemeData').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/field-configuration/scheme-data/edit/' + selected_rows[0];
    });

    $('#btnConfigureScreenScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/screen/configure-scheme/' + selected_rows[0];
    });

    $('#btnCopyScreenScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/screen/copy-scheme/' + selected_rows[0];
    });

    $('#btnCopyPermissionScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/permission-scheme/copy/' + selected_rows[0];
    });

    $('#btnCopyFieldConfigurationScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/field-configuration/scheme/copy/' + selected_rows[0];
    });

    $('#btnCopyFieldConfiguration').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/field-configuration/copy/' + selected_rows[0];
    });

    $('#btnCopyNotificationScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/notification-scheme/copy/' + selected_rows[0];
    });

    $('#btnEditFieldConfigurationScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/field-configuration/scheme/edit/' + selected_rows[0];
    });

    $('#btnConfigureIssueTypeScreenScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/screen/configure-scheme-issue-type/' + selected_rows[0];
    });

    $('#btnCopyIssueTypeScreenScheme').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/screen/scheme-issue-type/copy/' + selected_rows[0];
    });

    $('#btnEditScreen').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/screen/configure/' + selected_rows[0];
    });

    $('#btnCopyScreen').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/screen/copy/' + selected_rows[0];
    });

    $('#btnEditScreenMetaData').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/screen/edit/' + selected_rows[0];
    });

    $('#btnCustomizefields').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/screen/configure/' + selected_rows[0];
    });

    $('#btnEditWorkflow').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/workflow/edit/' + selected_rows[0];
    });
    $('#btnEditUserProjectRoles').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = $(this).attr('href');
    });

    $('#btnEditComponent').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/project/component/edit/' + selected_rows[0];
    });

    $('#btnCreateSubcomponent').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/project/subcomponent/add/' + $('#project_id').val() + '/' + selected_rows[0];
    });

    $('#btnEditSLACalendar').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/helpdesk/sla/calendar/edit/' + selected_rows[0];
    });

    $('#btnEditIssuePriority').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/issue/priority/edit/' + selected_rows[0];
    });

    $('#btnEditIssueStatus').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/issue/status/edit/' + selected_rows[0];
    });

    $('#btnEditIssueType').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/issue/edit-type/' + selected_rows[0];
    });

    $('#btnEditIssueResolution').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/issue/resolution/edit/' + selected_rows[0];
    });

    $('#btnEditClientProject').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/project/edit/' + selected_rows[0];
    });

    $('#btnEditRelease').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/project/version/edit/' + selected_rows[0];
    });

    $('#btnEditUserGroup').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/group/edit/' + selected_rows[0];
    });

    $('#btnEditPermRole').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/role/edit/' + selected_rows[0];
    });

    $('#btnEditCustomField').click(function () {
        if (selected_rows.length == 1)
            document.location.href = '/yongo/administration/custom-field/edit/' + selected_rows[0];
    });

    $("[id^='issue_transition_with_no_screen_']").click(function (event) {
        event.preventDefault();
        $('#menu_workflow').hide();
        var ids = $(this).attr("id").replace('issue_transition_with_no_screen_', '').split('_');
        var stepIdFrom = ids[0];
        var stepIdTo = ids[1];
        var workflowId = $('#workflow_used_id').val();
        var issueId = $('#issue_id').val();

        doTransitionWithoutScreen(issueId, stepIdFrom, stepIdTo, workflowId, function () {
            location.reload();
        });
    });

    $('#sectIssueTypes').click(function (event) {
        if ($('#contentIssueTypes').is(':visible'))
            $('#contentIssueTypes').hide();
        else
            $('#contentIssueTypes').show();
    });
    $('#sectWorkflows').click(function (event) {
        if ($('#contentWorkflows').is(':visible'))
            $('#contentWorkflows').hide();
        else
            $('#contentWorkflows').show();
    });
    $('#sectScreens').click(function (event) {
        if ($('#contentScreens').is(':visible'))
            $('#contentScreens').hide();
        else
            $('#contentScreens').show();
    });
    $('#sectFields').click(function (event) {
        if ($('#contentFields').is(':visible'))
            $('#contentFields').hide();
        else
            $('#contentFields').show();
    });
    $('#sectPeople').click(function (event) {
        if ($('#contentPeople').is(':visible'))
            $('#contentPeople').hide();
        else
            $('#contentPeople').show();
    });
    $('#sectProjectVersions').click(function (event) {
        if ($('#contentProjectVersions').is(':visible'))
            $('#contentProjectVersions').hide();
        else
            $('#contentProjectVersions').show();
    });
    $('#sectProjectComponents').click(function (event) {
        if ($('#contentProjectComponents').is(':visible'))
            $('#contentProjectComponents').hide();
        else
            $('#contentProjectComponents').show();
    });
    $('#sectPermissionScheme').click(function (event) {
        if ($('#contentPermissionScheme').is(':visible'))
            $('#contentPermissionScheme').hide();
        else
            $('#contentPermissionScheme').show();
    });
    $('#sectNotifications').click(function (event) {
        if ($('#contentNotifications').is(':visible'))
            $('#contentNotifications').hide();
        else
            $('#contentNotifications').show();
    });
    $('#sectDates').click(function (event) {
        if ($('#contentDates').is(':visible'))
            $('#contentDates').hide();
        else
            $('#contentDates').show();
    });
    $('#sectTimeTracking').click(function (event) {
        if ($('#contentTimeTracking').is(':visible'))
            $('#contentTimeTracking').hide();
        else
            $('#contentTimeTracking').show();
    });
    $('#sectDetails').click(function (event) {
        if ($('#contentDetails').is(':visible')) {
            $('#contentDetails').hide();
        } else {
            $('#contentDetails').show();
        }
    });
    $('#sectDescription').click(function (event) {
        if ($('#contentDescription').is(':visible')) {
            $('#contentDescription').hide();
        } else {
            $('#contentDescription').show();
        }
    });
    $('#sectSubTasks').click(function (event) {
        if ($('#contentSubTasks').is(':visible')) {
            $('#contentSubTasks').hide();
        } else {
            $('#contentSubTasks').show();
        }
    });
    $('#sectEnvironment').click(function (event) {
        if ($('#contentEnvironment').is(':visible')) {
            $('#contentEnvironment').hide();
        } else {
            $('#contentEnvironment').show();
        }
    });
    $('#sectActivity').click(function (event) {
        if ($('#contentActivity').is(':visible')) {
            $('#contentActivity').hide();
        } else {
            $('#contentActivity').show();
        }
    });
    $('#sectAttachments').click(function (event) {
        if ($('#contentAttachments').is(':visible')) {
            $('#contentAttachments').hide();
        } else {
            $('#contentAttachments').show();
        }
    });
    $('#sectCustomField').click(function (event) {
        if ($('#contentCustomField').is(':visible')) {
            $('#contentCustomField').hide();
        } else {
            $('#contentCustomField').show();
        }
    });
    $('#sectSearchIssueProperties').click(function (event) {
        if ($('#contentSearchIssueProperties').is(':visible')) {
            $('#contentSearchIssueProperties').hide();
        } else {
            $('#contentSearchIssueProperties').show();
        }
    });

    $('#sectSearchIssueDates').click(function (event) {
        if ($('#contentSearchIssueDates').is(':visible')) {
            $('#contentSearchIssueDates').hide();
        } else {
            $('#contentSearchIssueDates').show();
        }
    });

    $('#form_edit_user, #form_add_user').submit(function () {

        $("#assigned_user_groups").each(function () {
            $("#assigned_user_groups option").attr("selected", "selected");
        });

        $("#all_user_group").each(function () {
            $("#all_user_group option").attr("selected", "selected");
        });

        $("#assigned_projects").each(function () {
            $("#assigned_projects option").attr("selected", "selected");
        });
    });

    function retainFieldsData(fields) {
        var fieldsData = {};
        for (var i = 0; i < fields.length; i++) {
            var elementId = fields[i].getAttribute('id');

            if (!$('#' + elementId).is('span')) {
                if (elementId.indexOf('chzn') == -1) {
                    fieldsData[elementId] = $('#' + elementId).val();
                }
            }
        }

        return fieldsData;
    }

    function restoreFieldsData(fields, fieldsData) {
        for (var i = 0; i < fields.length; i++) {
            var elementId = fields[i].getAttribute('id');
            if (!$('#' + elementId).is('span') && elementId != 'field_type_attachment' && elementId != 'field_type_assignee') {
                $('#' + elementId).val(fieldsData[elementId]);
            }
        }
    }

    $(document).on('change', '#field_type_type', function (event) {
        var operationId = $('#operation_id').val();
        var projectId = $('#field_type_project').val();
        if (projectId == undefined) {
            projectId = $('#project_id').val();
        }

        if (operationId == 1) { // create issue
            $.ajax({
                type: "POST",
                url: '/yongo/issue/render-field-list',
                data: {
                    issue_type_id: $('#field_type_type').val(),
                    project_id: projectId,
                    operation_id: operationId
                },
                success: function (response) {
                    // get the already introduced data
                    var fields = $("[id^='field_type_'], [id^='field_custom_type_']");
                    var fieldsData = retainFieldsData(fields);

                    $('#tableFieldList').find("tr:gt(2)").remove();
                    $('#tableFieldList').append(response);

                    // restore fields data
                    restoreFieldsData(fields, fieldsData);

                    $("select.select2Input").select2();
                    var due_date_picker = $("#field_type_due_date");
                    if (due_date_picker.length) {
                        due_date_picker.datepicker({dateFormat: "yy-mm-dd"});
                    }

                    if (window.File && window.FileList && window.FileReader) {
                        initializaFileUpload();
                    }
                }
            });
        } else if (operationId == 2) { // edit issue
            var issueId = $('#issue_id').val();
            var issueTypeId = $('#field_type_type').val();

            $.ajax({
                type: "POST",
                url: '/yongo/issue/render-update-field-list',
                data: {
                    issue_id: issueId,
                    issue_type_id: issueTypeId
                },
                success: function (response) {
                    var fields = $("[id^='field_type_'], [id^='field_custom_type_']");
                    var fieldsData = retainFieldsData(fields);

                    $('#tableFieldList').html(response);

                    // restore fields data
                    restoreFieldsData(fields, fieldsData);

                    $(".select2Input").select2();
                    var due_date_picker = $("#field_type_due_date");
                    if (due_date_picker.length) {
                        due_date_picker.datepicker({dateFormat: "yy-mm-dd"});
                    }

                    if (window.File && window.FileList && window.FileReader) {
                        initializaFileUpload(issueId);
                    }
                }
            });
        }
    });

    $('#search_project_list').on('change', function (event) {

        var project_id_arr = [];
        $('#search_project_list > option:selected').each(function (i, selected) {
            project_id_arr[i] = $(this).val();
        });

        $.ajax({
            type: "POST",
            url: '/yongo/project/get-project-data',
            data: {
                project_id_arr: project_id_arr
            },
            success: function (response) {
                var obj = jQuery.parseJSON(response);

                $('#search_issue_type').find('option').remove();
                $('#search_issue_type').append($('<option></option>').val(-1).html('Any'));
                $.each(obj.type_arr, function (key, value) {
                    $('#search_issue_type').append($('<option></option>').val(value.id).html(value.name));
                });
                $('#search_issue_type').select2('val', $("#search_issue_type option:first").val());

                $('#search_issue_status').find('option').remove();
                $('#search_issue_status').append($('<option></option>').val(-1).html('Any'));
                $.each(obj.status_arr, function (key, value) {
                    $('#search_issue_status').append($('<option></option>').val(value.id).html(value.name));
                });
                $('#search_issue_status').select2('val', $("#search_issue_status option:first").val());

                $('#search_issue_priority').find('option').remove();
                $('#search_issue_priority').append($('<option></option>').val(-1).html('Any'));
                $.each(obj.priority_arr, function (key, value) {
                    $('#search_issue_priority').append($('<option></option>').val(value.id).html(value.name));
                });
                $('#search_issue_priority').select2('val', $("#search_issue_priority option:first").val());

                $('#search_issue_resolution').find('option').remove();
                $('#search_issue_resolution').append($('<option></option>').val(-1).html('Any'));
                $.each(obj.resolution_arr, function (key, value) {
                    $('#search_issue_resolution').append($('<option></option>').val(value.id).html(value.name));
                });
                $('#search_issue_resolution').select2('val', $("#search_issue_resolution option:first").val());

                $('#search_assignee').find('option').remove();
                $('#search_reporter').find('option').remove();
                $('#search_assignee').append($('<option></option>').val(-1).html('Any'));
                $('#search_reporter').append($('<option></option>').val(-1).html('Any'));

                $.each(obj.user_arr_assignable, function (key, value) {
                    $('#search_assignee').append($('<option></option>').val(value.user_id).html(value.first_name + ' ' + value.last_name));
                    $('#search_reporter').append($('<option></option>').val(value.user_id).html(value.first_name + ' ' + value.last_name));
                });

                $('#search_assignee').select2('val', $("#search_assignee option:first").val());
                $('#search_reporter').select2('val', $("#search_reporter option:first").val());

                // project components
                $('#search_component').find('option').remove();
                $('#search_component').append($('<option></option>').val(-1).html('Any'));
                $.each(obj.project_component_arr, function (key, value) {
                    $('#search_component').append($('<option></option>').val(value.id).html(value.name));
                });
                $('#search_component').select2('val', $("#search_component option:first").val());

                // project fix version
                $('#search_fix_version').find('option').remove();
                $('#search_fix_version').append($('<option></option>').val(-1).html('Any'));
                $.each(obj.project_version_arr, function (key, value) {
                    $('#search_fix_version').append($('<option></option>').val(value.id).html(value.name));
                });
                $('#search_fix_version').select2('val', $("#search_fix_version option:first").val());

                // project affects version
                $('#search_affects_version').find('option').remove();
                $('#search_affects_version').append($('<option></option>').val(-1).html('Any'));
                $.each(obj.project_version_arr, function (key, value) {
                    $('#search_affects_version').append($('<option></option>').val(value.id).html(value.name));
                });
                $('#search_affects_version').select2('val', $("#search_affects_version option:first").val());
            }
        });
    });

    $('#project_icon').mouseenter(function (event) {
        $('#project_icon').css('cursor', 'pointer');
    });

    $(document).on('click', "[id^='remove_watcher_']", function (event) {
        var watcherId = $(this).attr("id").replace('remove_watcher_', '');
        var parent = $(this).parent().parent();
        var watchers = $('#issueWatcherCount').html().replace('<b>', '').replace('</b>', '');
        watchers--;
        var element = $(this);
        $.ajax({
            type: "POST",
            url: '/issue/watchers/remove',
            data: {
                id: watcherId,
                issue_id: $('#issue_id').val()
            },
            success: function (response) {
                parent.remove();
                $('#issueWatcherCount').html('<b>' + watchers + ' </b>');
                if (watcherId == $('#user_id').val()) {
                    $('.toggle_watch_issue').html('Start watching this issue');
                    $('.toggle_watch_issue').attr('data', 'add');
                }

                var userFirstLastName = element.parent().parent().children().first().html();

                $('#user_to_watch').append('<option value="' + watcherId + '">' + userFirstLastName + '</option>');
            }
        });
    });

    $(document).on('click', "#add_watcher", function (event) {
        event.preventDefault();
        event.stopPropagation();

        var usersToWatch = $('#user_to_watch').val();
        $.ajax({
            type: "POST",
            url: '/issue/watchers/add',
            data: {
                id: usersToWatch,
                issue_id: $('#issue_id').val()
            },
            success: function (response) {
                $('#contentAddIssueWatcher').hide();
                var watchers = parseInt($('#issueWatcherCount').html().replace('<b>', '').replace('</b>', '').trim());
                watchers += $('#user_to_watch').val().length;
                $('#issueWatcherCount').html('<b>' + watchers + ' </b>');

                for (var i = 0; i < usersToWatch.length; i++) {
                    if (usersToWatch[i] == $('#user_id').val()) {
                        $('.toggle_watch_issue').html('Stop watching this issue');
                        $('.toggle_watch_issue').attr('data', 'remove');
                    }
                }
            }
        });
    });

    $('.toggle_watch_issue').click(function (event) {
        event.preventDefault();
        event.stopPropagation();

        $('#contentAddIssueWatcher').hide();
        var action = $(this).attr('data');
        var watchers = $('#issueWatcherCount').html().replace('<b>', '').replace('</b>', '');

        $.ajax({
            type: "POST",
            url: '/issue/watchers/toggle',
            data: {
                id: $('#issue_id').val(),
                action: action
            },
            success: function (response) {
                if (action == 'add') {
                    $('.toggle_watch_issue').html('Stop watching this issue');
                    watchers++;
                    $('#issueWatcherCount').html('<b>' + watchers + ' </b>');
                    $('.toggle_watch_issue').attr('data', 'remove');
                } else if (action == 'remove') {
                    $('.toggle_watch_issue').html('Start watching this issue');
                    watchers--;
                    $('#issueWatcherCount').html('<b>' + watchers + ' </b>');
                    $('.toggle_watch_issue').attr('data', 'add');
                }
            }
        });
    });

    $('#issueWatcherCount').click(function (event) {
        event.preventDefault();
        event.stopPropagation();

        var hasViewVotersAndWatchersPermission = $('#has_view_voters_and_watchers_permission').val();
        if (hasViewVotersAndWatchersPermission) {
            $.ajax({
                type: "POST",
                url: '/issue/watchers/dialog/add',
                data: {
                    id: $('#issue_id').val()
                },
                success: function (response) {
                    $('#contentAddIssueWatcher').css('left', $('#issueWatcherCount').position().left);
                    $('#contentAddIssueWatcher').css('top', $('#issueWatcherCount').position().top + 28);
                    $('#contentAddIssueWatcher').css('z-index', '500');
                    $('#contentAddIssueWatcher').css('padding', '4px');

                    $('#contentAddIssueWatcher').css('position', 'absolute');
                    $('#contentAddIssueWatcher').css('border', '1px solid lightgray');
                    $('#contentAddIssueWatcher').css('width', '280px');
                    $('#contentAddIssueWatcher').css('background-color', '#ffffff');
                    $('#contentAddIssueWatcher').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
                    $('#contentAddIssueWatcher').show();
                    $('#contentAddIssueWatcher').html(response);
                    $(".select2Input").select2();
                }
            });
        }
    });

    $("#btnStartBackup").on('click', function (event) {
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: '/backup',
            data: {

            },
            success: function (response) {
                $('#current_backup').html(response);
            }
        });
    });

    $('#header_checkbox').on('click', function (event) {
        if ($('#header_checkbox').is(':checked'))
            $("[id^='el_check_']").prop('checked', true);
        else
            $("[id^='el_check_']").prop('checked', false);
    });

    $(document).on('click', "#closeWatchedDialog", function (event) {
        $('#contentAddIssueWatcher').hide();
    });

    $('#ubirimi_quick_search').keyup(function (event) {
        if (event.keyCode == 13) {

            var value = $('#ubirimi_quick_search').val();

            $.ajax({
                type: "POST",
                url: '/yongo/issue/quick-search',
                data: {
                    code: value
                },
                success: function (response) {
                    if (response != '-1')
                        window.location.href = '/yongo/issue/' + response;
                    else
                        window.location.href = '/yongo/issue/search?search_query=' + value + '&summary_flag=1&description_flag=1&comments_flag=1&project=' + $('#projects_for_browsing').val();
                }
            });
        }
    });

    // created vs. resolved chart
    function drawChartCreatedVsResolved() {
        var projectId = $('#chart_project_created_resolved').val();
        if (!projectId) {
            projectId = $('#project_id').val();
        }

        $.ajax({
            type: "POST",
            url: '/yongo/chart/get/created-vs-resolved',
            dataType: 'json',
            data: {
                id: projectId
            },
            success: function (chartData) {

                var dates = [];
                var created = [];
                var resolved = [];
                for (var i = 0; i < chartData.length; i++) {
                    dates.push(chartData[i][0]);
                    created.push(chartData[i][1]);
                    resolved.push(chartData[i][2]);
                }

                $('#chart_created_resolved').highcharts({
                    title: {
                        text: 'Created vs Resolved',
                        x: -20 //center
                    },
                    xAxis: {
                        categories: dates,
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    },
                    yAxis: {
                        title: {
                            text: '# Issues'
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: [{
                        name: 'Created',
                        data: created
                    }, {
                        name: 'Resolved',
                        data: resolved
                    }]
                });
            }
        });
    }

    if ($('#chart_created_resolved').length) {
        drawChartCreatedVsResolved();
    }

    $(document).on('change', '#chart_project_created_resolved', function (event) {
        drawChartCreatedVsResolved();
    });

    $(document).on('change', '#gadget_unresolved_others_project', function (event) {
        $.ajax({
            type: "POST",
            url: '/yongo/chart/get/unresolved-others',
            data: {
                id: $('#gadget_unresolved_others_project').val()
            },
            success: function (response) {
                $('#content_gadget_unresolved_others').html(response);
                $("select.select2InputMedium").select2();
            }
        });
    });

    $(document).on('click', "[id^='add_project_history_comment_']", function (event) {
        var issueId = $(this).attr("id").replace('add_project_history_comment_', '').split('_')[0];
        var index = $(this).attr("id").replace('add_project_history_comment_', '').split('_')[1];
        $.ajax({
            type: "POST",
            url: '/yongo/activity-stream/comment/add/render',
            data: {
                id: issueId,
                index: index
            },
            success: function (response) {
                $('#project_history_comment_' + issueId + '_' + index).html(response).show().css('background-color', 'white');
            }
        });
    });

    $(document).on('click', "[id^='save_comment_project_activity_']", function (event) {
        var issueId = $(this).attr("id").replace('save_comment_project_activity_', '').split('_')[0];
        var index = $(this).attr("id").replace('save_comment_project_activity_', '').split('_')[1];

        $.ajax({
            type: "POST",
            url: '/yongo/comment/add',
            data: {
                id: issueId,
                content: $('#content_add_comment_project_activity_' + issueId + '_' + index).val()
            },
            success: function (response) {
                $('#project_history_comment_' + issueId + '_' + index).html('Successfully added the comment').css('background-color', '#e2f5c7').fadeOut(3000);
            }
        });
    });

    $(document).on('click', "[id^='cancel_comment_project_activity_']", function (event) {
        var issueId = $(this).attr("id").replace('cancel_comment_project_activity_', '').split('_')[0];
        var index = $(this).attr("id").replace('cancel_comment_project_activity_', '').split('_')[1];
        $('#project_history_comment_' + issueId + '_' + index).hide();
    });

    $(document).on('change', '#gadget_two_dimensional_filter', function (event) {
        $.ajax({
            type: "POST",
            url: '/yongo/chart/get/two-dimensional-filter',
            data: {
                id: $('#gadget_two_dimensional_filter').val()
            },
            success: function (response) {
                $('#content_gadget_two_dimensional_filter').html(response);
                $("select.select2InputMedium").select2();
            }
        });
    });

    $('.calendar-issue-box').each(function () {
        $(this).qtip({
            show: {
                when: 'click'
            },
            content: {
                text: $(this).next('div')
            },
            hide: {
                fixed: true,
                leave: false,
                delay: 200
            },
            position: {
                adjust: {
                    x: -10
                }
            }
        });
    });

    var issueSearchDateFilters = [$("#search_date_due_after"), $('#search_date_due_before'), $('#search_date_created_before'), $('#search_date_created_after')];

    for (var i = 0; i < issueSearchDateFilters.length; i++) {
        if (issueSearchDateFilters[i].length) {
            issueSearchDateFilters[i].datepicker({dateFormat: "yy-mm-dd"});
        }
    }

    $(document).on('click', '#btnIssueSearchColumns', function (event) {
        event.preventDefault();

        if ($('#content_chose_display_columns').is(':visible') && $('#content_chose_display_columns').html()) {
            $('#content_chose_display_columns').hide();
            return
        }

        var leftOffset = $(window).width() - ($(window).width() - $('#btnIssueSearchColumns').position().left) - 240 + $('#btnIssueSearchColumns').width() - 5;

        $('#content_chose_display_columns').css('left', leftOffset);
        $('#content_chose_display_columns').css('top', $('#btnIssueSearchColumns').position().top + 28);
        $('#content_chose_display_columns').css('z-index', '500');
        $('#content_chose_display_columns').css('padding', '4px');

        $('#content_chose_display_columns').css('position', 'absolute');
        $('#content_chose_display_columns').css('width', '240px');
        $('#content_chose_display_columns').css('background-color', '#ffffff');
        $('#content_chose_display_columns').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#content_chose_display_columns').show();
    });

    $(document).on('click', '#btnIssueSearchColumnsCancel', function (event) {
        $('#content_chose_display_columns').hide();
    });

    $(document).on('click', '#perm_choose_user', function (event) {
        $('#label_user_permission').prop('checked', true);
    });
    $(document).on('click', '#not_choose_user', function (event) {
        $('#label_user_notification').prop('checked', true);
    });

    $(document).on('click', '#perm_choose_group', function (event) {
        $('#label_group_permission').prop('checked', true);
    });
    $(document).on('click', '#not_choose_group', function (event) {
        $('#label_group_notification').prop('checked', true);
    });

    $(document).on('click', '#perm_choose_project_role', function (event) {
        $('#label_project_role_permission').prop('checked', true);
    });
    $(document).on('click', '#not_choose_project_role', function (event) {
        $('#label_project_role_notification').prop('checked', true);
    });
    $(document).on('click', '#not_select_user_picker_multiple_selection', function (event) {
        $('#label_user_picker_multiple_selection').prop('checked', true);
    });

    $(document).on('change', '#move_to_project', function (event) {
        $.ajax({
            type: "POST",
            url: '/yongo/project/get-issue-types',
            data: {
                id: $('#move_to_project').val()
            },
            success: function (response) {
                var obj = jQuery.parseJSON(response);

                $('#move_to_issue_type').find('option').remove();
                $.each(obj, function (key, value) {
                    $('#move_to_issue_type').append($('<option></option>').val(value.id).html(value.name));
                    $('#move_to_issue_type').select2('val', $("#move_to_issue_type option:first").val());
                });
            }
        });
    });

    $(document).on('click', '#btnUpdateIssueSearchColumns', function (event) {
        event.stopPropagation();
        event.preventDefault();

        $('#content_chose_display_columns').hide();
        var checkedColumns = $("[id^='issue_column_']:checked");
        var columns = [];
        for (var i = 0; i < checkedColumns.length; i++) {
            columns.push(checkedColumns[i].id.replace('issue_column_', ''));
        }

        var queueContext = $('#queue_context').val();
        if (queueContext) {
            $.ajax({
                type: "POST",
                url: '/helpdesk/queue/set-display-issues-columns',
                data: {
                    id: $('#queue_id').val(),
                    data: columns.join('#')
                },
                success: function (response) {
                    window.location.reload();
                }
            });

        } else {
            $.ajax({
                type: "POST",
                url: '/yongo/user/set-display-issues-columns',
                data: {
                    data: columns.join('#')
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });

    $(document).on('click', '#get_next_activity', function (event) {

        var date = $('.activity_last_date')[$('.activity_last_date').length - 1].value;
        var project = 'all';
        if ($('#menu_selected').val() == 'project') {
            project = $('#project_id').val();
        }
        $.ajax({
            type: "POST",
            url: '/yongo/get-activity-stream-chunk',
            data: {
                date: date,
                project: project
            },
            success: function (response) {
                $('.nextActivityChunk')[$('.nextActivityChunk').length - 1].innerHTML = response;
            }
        });
    });

    function filterUserList() {
        $.ajax({
            type: "POST",
            url: '/yongo/administration/filter/users',
            data: {
                username_filter: $('#username_filter').val(),
                fullname_filter: $('#fullname_filter').val(),
                group_filter: $('#group_filter').val()
            },
            success: function (response) {
                $('#contentListUsers').html(response);
            }
        });
    }

    $(document).on('change', '#group_filter', function (event) {
        filterUserList();
    });

    $(document).on('keyup', '#username_filter, #fullname_filter', function (event) {
        filterUserList();
    });

    $(document).on('keyup', '#group_name_filter', function (event) {
        $.ajax({
            type: "POST",
            url: '/yongo/administration/filter/groups',
            data: {
                name_filter: $('#group_name_filter').val()
            },
            success: function (response) {
                $('#contentListGroups').html(response);
            }
        });
    });

    $("[id^='toggle_filter_favourite_']").click(function (event) {

        var filterId = $(this).attr("id").replace('toggle_filter_favourite_', '');
        $.ajax({
            type: "POST",
            url: '/yongo/filter/favourite/toggle',
            data: {
                id: filterId
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    // filter crontab
    $(document).on('click', '#minute_chooser_choose', function (event) {
        $('#cron_minute').prop('disabled', false);
    });
    $(document).on('click', '#minute_chooser_every', function (event) {
        $('#cron_minute').prop('disabled', 'disabled');
    });
    $(document).on('click', '#hour_chooser_choose', function (event) {
        $('#cron_hour').prop('disabled', false);
    });
    $(document).on('click', '#hour_chooser_every', function (event) {
        $('#cron_hour').prop('disabled', 'disabled');
    });
    $(document).on('click', '#day_chooser_choose', function (event) {
        $('#cron_day').prop('disabled', false);
    });
    $(document).on('click', '#day_chooser_every', function (event) {
        $('#cron_day').prop('disabled', 'disabled');
    });
    $(document).on('click', '#month_chooser_choose', function (event) {
        $('#cron_month').prop('disabled', false);
    });
    $(document).on('click', '#month_chooser_every', function (event) {
        $('#cron_month').prop('disabled', 'disabled');
    });
    $(document).on('click', '#weekday_chooser_choose', function (event) {
        $('#cron_weekday').prop('disabled', false);
    });
    $(document).on('click', '#weekday_chooser_every', function (event) {
        $('#cron_weekday').prop('disabled', 'disabled');
    });
});