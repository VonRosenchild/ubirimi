$('document').ready(function () {

    $(".select2Input").select2();
    $(".select2InputSmall").select2();
    $(".select2InputMedium").select2();

    $('#cal_event_edit_date_from, #cal_event_edit_date_to').datetimepicker({
        timeFormat: "hh:mm",
        dateFormat: "yy-mm-dd",
        ampm: false
    });

    $('#btnEditCalendar').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/calendar/edit/' + selected_rows[0];
    });

    $('#btnCalendarSettings').click(function (event) {
        event.preventDefault();
        if (selected_rows.length == 1)
            document.location.href = '/calendar/settings/' + selected_rows[0];
    });

    $('#btnCreateCalendar').click(function (event) {
        document.location.href = '/calendar/add';
    });

    $("[id^='select_calendar_']").on('change', function (event) {
        event.preventDefault();
        var calendarIds = [];
        if ($("[id^='select_calendar_']:checked").length) {
            $("[id^='select_calendar_']:checked").each(function() {
                var id = this.id.replace('select_calendar_', '');
                calendarIds.push(id);
                var calendarIdsString = calendarIds.join('|');
                window.location.href = '/calendar/view/' + calendarIdsString + '/' + $('#cal_current_month').val() + '/' + $('#cal_current_year').val()
            });
        } else {
            window.location.href = '/calendar/view/-1/' + $('#cal_current_month').val() + '/' + $('#cal_current_year').val()
        }
    });

    $(document).on('change', "#event_calendar", function (event) {
        var color = $(this).val().split('_')[1];
        var myPicker = new jscolor.color(document.getElementById('color_event_parent'), {valueElement: 'event_color'});
        myPicker.fromString(color);  // now you can access API via 'myPicker' variable
    });

    $('#menuCalendars').mouseenter(function (event) {
        $('#menuCalendars').css('cursor', 'pointer');
    });

    $('#menuCalendars').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        if ($('#contentMenuCalendars').is(':visible') && $('#contentMenuCalendars').html()) {
            $('#contentMenuCalendars').hide();
            return
        }

        $('#menuCalendars').css('background-color', '#cccccc');
        $('#menu_top_user').css('background-color', '#003466');

        $('#contentUserHome').hide();

        $('#contentMenuCalendars').css('left',$('#menuCalendars').position().left)
        $('#contentMenuCalendars').css('top', $('#menuCalendars').position().top + 28)
        $('#contentMenuCalendars').css('z-index', '500');
        $('#contentMenuCalendars').css('padding', '4px');

        $('#contentMenuCalendars').css('position', 'absolute');
        $('#contentMenuCalendars').css('width', '200px');
        $('#contentMenuCalendars').css('background-color', '#ffffff');
        $('#contentMenuCalendars').css('box-shadow', '3px 3px 5px rgba(0, 0, 0, 0.5)');
        $('#contentMenuCalendars').show();
    });

    $('.cal-event').each(function() {

        $(this).qtip({
            show: 'click',
            content: {
                text: $(this).next()
            },
            hide: {
                event: 'click',
                target: $('.cal-event')
            },
            position: {
                my: 'bottom center',  // Position my top left...
                at: 'top center', // at the bottom right of...
                target: false

            },
            events: {
                show: function(event, api) {
                    $('.qtip').qtip('hide');
                }
            }
        });
    });

    $('#calendar_quick_search').keyup(function(event) {
        if (event.keyCode == 13) {

            var value = $('#calendar_quick_search').val();
            window.location.href = '/calendar/search?search_query=' + value;
        }
    });

    $(document).on('change', '#add_event_repeat_type', function (event) {
        var repeatType = $('#add_event_repeat_type').val();
        if (-1 == repeatType) {
            $('#add_event_repeat_daily_content').hide();
        }

        if (1 == repeatType) {
            $('#add_event_repeat_weekly_content').hide();
            $('#add_event_repeat_daily_content').show();
        }
        if (2 == repeatType) {
            $('#add_event_repeat_daily_content').hide();
            $('#add_event_repeat_weekly_content').show();
        }

        jQuery("#modalAddEvent").dialog('option', 'position', ['middle','middle']);

    });

    $(document).on('click', "[id^='event_delete_']", function (event) {

        var eventId = $(this).attr("id").replace('event_delete_', '');
        $.ajax({
            type: "POST",
            url: '/calendar/event/delete',
            data: {
                id: eventId
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $(document).on('click', "[id^='only_this_instance_delete_event_']", function (event) {

        var eventId = $(this).attr("id").replace('only_this_instance_delete_event_', '');
        $.ajax({
            type: "POST",
            url: '/calendar/event/delete',
            data: {
                id: eventId,
                recurring: 'me'
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $(document).on('click', "[id^='all_following_delete_event_']", function (event) {

        var eventId = $(this).attr("id").replace('all_following_delete_event_', '');
        $.ajax({
            type: "POST",
            url: '/calendar/event/delete',
            data: {
                id: eventId,
                recurring: 'all_following'
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $(document).on('click', "[id^='all_events_delete_event_']", function (event) {

        var eventId = $(this).attr("id").replace('all_events_delete_event_', '');
        $.ajax({
            type: "POST",
            url: '/calendar/event/delete',
            data: {
                id: eventId,
                recurring: 'all_series'
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    $("#event_add_reminder").on('click', function (event) {
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: '/calendar/render-new-reminder',
            data: {
            },
            success: function (response) {
                $("#content_event_reminders").append(response);
            }
        });
    });

    $(document).on('click', "[id^='delete_reminder_']", function (event) {
        event.preventDefault();
        var reminderId = $(this).attr("id").replace('delete_reminder_', '').split('_')[0];

        if (reminderId != 0) {
            $.ajax({
                type: "POST",
                url: '/calendar/reminder/delete',
                data: {
                    id: reminderId
                },
                success: function (response) {
                    $('#reminder_content_' + reminderId).remove();
                }
            });
        } else {

            var uniqueId = $(this).attr("id").replace('delete_reminder_', '').split('_')[1];

            $('#reminder_content_' + uniqueId).remove();
        }

    });

    $(document).on('click', "[id^='delete_calendar_reminder_']", function (event) {
        event.preventDefault();
        var calendarReminderId = $(this).attr("id").replace('delete_calendar_reminder_', '').split('_')[0];

        if (calendarReminderId != 0) {
            $.ajax({
                type: "POST",
                url: '/calendar/reminder-calendar/delete',
                data: {
                    id: calendarReminderId
                },
                success: function (response) {
                    $('#reminder_content_' + calendarReminderId).remove();
                }
            });
        } else {

            var uniqueId = $(this).attr("id").replace('delete_calendar_reminder_', '').split('_')[1];

            $('#reminder_content_' + uniqueId).remove();
        }

    });

    $(document).on('click', "[id^='show_all_events_']", function (event) {
        event.preventDefault();
        event.stopPropagation();
        var identifier = $(this).attr("id").replace('show_all_events_', '');
        $('#content_all_events_' + identifier).css('position', 'absolute');
        $('#content_all_events_' + identifier).css('top', '0px');
        $('#content_all_events_' + identifier).css('left', '00px');
        $('#content_all_events_' + identifier).css('z-index', '100000000');
        $('#content_all_events_' + identifier).show();

    });

    $(document).on('click', "[id^='close_content_all_events_']", function (event) {
        event.preventDefault();
        event.stopPropagation();
        var identifier = $(this).attr("id").replace('close_content_all_events_', '');
        $('#content_all_events_' + identifier).hide();

    });
});