<table class="modal-table">
    <tr>
        <td valign="top">Name</td>
        <td>
            <input type="text" value="" class="inputText" id="event_name" />
            <div class="mandatory" id="eventNameEmpty"></div>
        </td>
    </tr>
    <tr>
        <td valign="top">Description</td>
        <td><textarea class="inputTextAreaLarge" id="event_description"></textarea></td>
    </tr>
    <tr>
        <td valign="top">Location</td>
        <td><input type="text" value="" class="inputText" id="event_location" /></td>
    </tr>
    <tr>
        <td>Calendar</td>
        <td>
            <select name="calendar" class="select2InputMedium" id="event_calendar">
                <?php foreach ($calendars as $calendar): ?>
                    <option value="<?php echo $calendar['id'] ?>_<?php echo $calendar['color'] ?>"><?php echo $calendar['name'] ?></option>
                <?php endforeach ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>Interval</td>
        <td>
            <input style="width: 134px"
                   class="inputText" type="text"
                   id="event_start_date" value="<?php echo $defaultEventStartDate ?>" />
            <span>to</span>
            <input style="width: 134px"
                   class="inputText" type="text"
                   id="event_end_date" value="<?php echo $defaultEventStartDate ?>" />
        </td>
    </tr>
    <tr>
        <td>Color</td>
        <td>
            <input id="color_event_parent" class="inputText color {valueElement:'event_color'}"
                   style="width: 30px; cursor: pointer" name="color" value="" />
            <input type="hidden" id="event_color" value="<?php echo $firstCalendar['color'] ?>" />
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <hr size="1" />
        </td>
    </tr>
    <tr>
        <td>Repeats</td>
        <td>
            <select id="add_event_repeat_type" class="select2InputSmall">
                <option value="-1">Does not repeat</option>
                <option value="1">Daily</option>
                <option value="2">Weekly</option>
            </select>
        </td>
    </tr>
    <?php require_once __DIR__ . '/AddRepeatSettings.php' ?>
</table>