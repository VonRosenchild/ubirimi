
function getVisibleHeightOfElement(container) {
    //Container
    var cont = container;

    //Scroll Position (varies with scroll)
    var pageTop = $(document).scrollTop();
    //Visible Page Size (fixed)
    var pageSize = $(window).height();


    //Content top (fixed)
    var contTop = cont.offset().top;
    //Content top position (varies with scroll)
    var contTopPos = contTop - pageTop;
    //Content bottom (fixed)
    var contBottom =  cont.height() + contTop;
    //Content position in relation to screen top (varies with scroll)
    var contBottomPos = contBottom - pageTop;

    /*
     VISIBLE AREA
     Take the size of screen/page, unless the bottom of the content further up
     and subtract from it
     The header height, unless the top of the content is below the header
     */
    var visibleArea = Math.min(pageSize, contBottomPos) - Math.max( 0, contTopPos);

    return visibleArea;
}

$('document').ready(function () {

    $(".select2Input").select2();
    $(".select2InputSmall").select2();
    $(".select2InputMedium").select2();

    $('#agile_wrapper_planning').css('height', $(window).height() - 215);
    $('#agile_wrapper_work').css('height', $(window).height() - 187);
    $('#agileIssueContent').css('height', $(window).height() - 215);

    $(document).on('click', "[id^='agile_create_subtask_']", function (event) {

        var issueId = $(this).attr("id").replace('agile_create_subtask_', '').split('_')[0];
        var projectId = $(this).attr("id").replace('agile_create_subtask_', '').split('_')[1];

        function reloadIssueData() {
            $.ajax({
                type: "POST",
                url: '/agile/render-issue',
                data: {
                    id: issueId,
                    close: 1
                },
                success: function (response) {
                    var content = 'content_agile_issue';
                    if ($('#content_agile_issue').length == 0)
                        content = 'agileIssueContent';

                    $('#' + content + ':first-child:first-child').children().first().html(response);
                    $("[id^='tab_issue_agile_content_basic']").removeClass('active');
                    $('#content_issue_agile_content_basic').hide();
                    $('#content_issue_agile_content_subtasks').show();
                    $('#tab_issue_agile_content_subtasks').attr('class', 'active');
                }
            });
        }

        var swimlaneStrategy = $('#agile_swimlane_strategy').val();

        if (swimlaneStrategy == 'story') {
            createSubtask(issueId, projectId, function () {
                location.reload()
            });
        } else {
            createSubtask(issueId, projectId, reloadIssueData);
        }
    });

    $(document).on('click', "[id^='agile_plan_assign_to_me_']", function (event) {

        event.preventDefault();
        var showCloseButton = 0;
        if ($('#content_agile_issue').length) {
            showCloseButton = 1;
        }
        var issueId = $(this).attr("id").replace('agile_plan_assign_to_me_', '').split('_')[0];
        var refresh = $(this).attr("id").replace('agile_plan_assign_to_me_', '').split('_')[1];
        $.ajax({
            type: "POST",
            url: '/yongo/issue/assign-to-me',
            data: {
                id: issueId
            },
            success: function (response) {
                if (refresh) {
                    window.location.reload();
                } else {
                    $.ajax({
                        type: "POST",
                        url: '/agile/render-issue',
                        data: {
                            id: issueId,
                            close: showCloseButton
                        },
                        success: function (response) {
                            if ($('#agileIssueContent').length) {
                                $('#agileIssueContent').html(response);
                            } else if ($('#content_agile_issue').length) {
                                $('#content_agile_issue > div').html(response);
                            }
                        }
                    });
                }
            }
        });
    });

    $("[id^='add_to_sprint_']").click(function (event) {
        event.preventDefault();
        $('#menu_add_to_sprint').hide();

        if (!selected_rows.length)
            return;

        var sprintId = $(this).attr("id").replace('add_to_sprint_', '');

        $.ajax({
            type: "POST",
            url: '/agile/add-issue-to-sprint',
            data: {
                id: sprintId,
                issue_id: selected_rows
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $(document).on('click', "[id^='close_agile_issue_content']", function (event) {

        event.preventDefault();

        $('#content_agile_issue').html('');
        $('#content_agile_issue').parent().width(1);
        $('#content_agile_issue').parent().css('padding-left', '0px');
        $('#content_agile_issue').width(1);

        if ($('#wrapper_content_agile_work').length) {
            $('#wrapper_content_agile_work').css('display', 'none');
        }
    });

    $("[id^='agile_issue_']").click(function (event) {
        event.preventDefault();
        var issueId = $(this).attr("id").replace('agile_issue_', '');
        $.ajax({
            type: "POST",
            url: '/agile/render-issue',
            data: {
                id: issueId,
                close: 1
            },
            success: function (response) {
                var countColumns = $('#count_columns').val();
                var html = '<div style="border: 1px solid #b3b3b3; padding: 10px">' + response + '</div>';
                $('#content_agile_issue').css('width', '100%');
                $('#content_agile_issue').css('height', '100%');
                $('#content_agile_issue').parent().css('padding-left', '14px');
                $('#content_agile_issue').parent().width(($(document).width() - (countColumns - 1) * 14 - 300)  / countColumns);
                $('#content_agile_issue').html(html);
                $('#wrapper_content_agile_work').show();
            }
        });
    });

    $("#swimlane_strategy").on('change', function (event) {
        event.preventDefault();
        var strategy = $("#swimlane_strategy").val();
        $.ajax({
            type: "POST",
            url: '/agile/update-swimlane-strategy',
            data: {
                id: $('#board_id').val(),
                strategy: strategy
            },
            success: function (response) {
                if (strategy == 'story') {
                    $('#swimlane_strategy_description').html('Group sub-tasks under their parent issue. Issues without sub-tasks will be shown in their own group at the bottom.');
                } else if (strategy == 'assignee') {
                    $('#swimlane_strategy_description').html('Group issues by their assignee.');
                } else if (strategy == 'no_swimlane') {
                    $('#swimlane_strategy_description').html('No swimlanes will be displayed.');
                }
            }
        });
    });

    $('#agile_quick_search').change(function (event) {
        var location = window.location.href;
        var base = location.split("?")[0];
        var param = [];

        var newLocation = base;
        if (location.indexOf('only_my') != -1)
            param.push('only_my=1');

        if ($('#agile_quick_search').val())
            param.push('q=' + $('#agile_quick_search').val().trim());

        if (param.length)
            newLocation += '?' + param.join('&');
        window.location.href = newLocation;
    });
    $(document).on('click', "[id^='tab_issue_agile_content_']", function (event) {
        event.preventDefault();
        $("[id^='tab_issue_agile_content_']").removeClass('active');
        var category = $(this).attr("id").replace('tab_issue_agile_content_', '');
        $("[id^='content_issue_agile_content_']").hide();
        $('#content_issue_agile_content_' + category).show();
        $('#tab_issue_agile_content_' + category).attr('class', 'active');
    });

    $('#btnMoveToBacklog').on('click', function (event) {
        event.preventDefault();

        if ($(this).hasClass('disabled'))
            return;

        var issuesToMove = [];
        $("[id^='el_check_']:checked").each(function() {
            var id = this.id.replace('el_check_', '');
            issuesToMove.push(id);
        });
        $.ajax({
            type: "POST",
            url: '/agile/move-to-backlog',
            data: {
                id: issuesToMove
            },
            success: function (response) {
                window.location.reload();
            }
        });
    });
});