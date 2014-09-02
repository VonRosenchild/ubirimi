<div id="reminder_content_<?php
    use Ubirimi\Calendar\Repository\CalendarEventReminderPeriod;
    use Ubirimi\Calendar\Repository\CalendarReminderType;

    echo $uniqueId ?>">
    <select name="reminder_type_<?php echo $uniqueId; ?>" class="select2InputSmall">
        <option value="<?php
            echo CalendarReminderType::REMINDER_EMAIL ?>">Email</option>
    </select>
    &nbsp;
    <input type="text" value="30" name="value_reminder_<?php echo $uniqueId; ?>" style="width: 50px;" />
    &nbsp;
    <select name="reminder_period_<?php echo $uniqueId; ?>" class="select2InputSmall">
        <option value="<?php echo CalendarEventReminderPeriod::PERIOD_MINUTE ?>">minutes</option>
        <option value="<?php
            echo CalendarEventReminderPeriod::PERIOD_HOUR ?>">hours</option>
        <option value="<?php
            echo CalendarEventReminderPeriod::PERIOD_DAY ?>">days</option>
        <option value="<?php
            echo CalendarEventReminderPeriod::PERIOD_WEEK ?>">weeks</option>
    </select>
    <img src="/img/delete.png" id="delete_reminder_0_<?php echo $uniqueId ?>" title="Delete reminder" />
    <br />
</div>