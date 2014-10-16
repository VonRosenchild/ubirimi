Would you like to delete only this event, all events in the series, or this and all future events in the series?

<table cellspacing="2px" cellpadding="2px">
    <tr>
        <td>
            <input id="only_this_instance_delete_event_<?php echo $eventId ?>" type="button" style="width: 170px;" value="Only this instance" />
        </td>
        <td>
            <div>All other events in the series will remain.</div>
        </td>
    </tr>
    <tr>
        <td>
            <input id="all_following_delete_event_<?php echo $eventId ?>" type="button" style="width: 170px;" value="All following" />
        </td>
        <td>
            <div>This and all the following events will be deleted.</div>
        </td>
    </tr>
    <tr>
        <td>
            <input id="all_events_delete_event_<?php echo $eventId ?>" type="button" style="width: 170px;" value="All events in the series" />
        </td>
        <td>
            <div>All events in the series will be deleted.</div>
        </td>
    </tr>
</table>