<?php

use Ubirimi\Calendar\Repository\Reminder\ReminderPeriod;
use Ubirimi\Calendar\Repository\Reminder\ReminderType;
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/calendar/calendars">Calendars</a> > ' . $calendar['name'] . ' > Settings') ?>

    <div class="pageContent">
        <div class="headerPageText">Default Reminders</div>
        <hr size="1" />
        <form name="edit_status" action="/calendar/settings/<?php echo $calendarId ?>" method="post">
            <div id="content_event_reminders">
                <?php if ($defaultReminders): ?>

                    <?php while ($defaultReminders && $defaultReminder = $defaultReminders->fetch_array(MYSQLI_ASSOC)): ?>
                        <div id="reminder_content_<?php echo $defaultReminder['id'] ?>">
                            <span>By default, remind me via</span>
                            <br />
                            <select name="reminder_type_<?php echo $defaultReminder['id'] ?>" class="select2InputSmall">
                                <option value="<?php echo ReminderType::REMINDER_EMAIL ?>">Email</option>
                            </select>
                            &nbsp;
                            <input type="text"
                                   class="inputText"
                                   value="<?php echo $defaultReminder['value'] ?>"
                                   name="value_reminder_<?php echo $defaultReminder['id'] ?>"
                                   style="width: 50px;" />
                            &nbsp;
                            <select name="reminder_period_<?php echo $defaultReminder['id'] ?>" class="select2InputSmall">
                                <option <?php
                                    if ($defaultReminder['cal_event_reminder_period_id'] == ReminderPeriod::PERIOD_MINUTE) echo 'selected="selected"' ?> value="<?php echo ReminderPeriod::PERIOD_MINUTE ?>">minutes</option>
                                <option <?php if ($defaultReminder['cal_event_reminder_period_id'] == ReminderPeriod::PERIOD_HOUR) echo 'selected="selected"' ?> value="<?php echo ReminderPeriod::PERIOD_HOUR ?>">hours</option>
                                <option <?php
                                    if ($defaultReminder['cal_event_reminder_period_id'] == ReminderPeriod::PERIOD_DAY) echo 'selected="selected"' ?> value="<?php echo ReminderPeriod::PERIOD_DAY ?>">days</option>
                                <option <?php if ($defaultReminder['cal_event_reminder_period_id'] == ReminderPeriod::PERIOD_WEEK) echo 'selected="selected"' ?> value="<?php
                                    echo ReminderPeriod::PERIOD_WEEK ?>">weeks</option>
                            </select>

                            <img src="/img/delete.png" id="delete_calendar_reminder_<?php echo $defaultReminder['id'] ?>" title="Delete reminder" />
                            <br />
                        </div>
                    <?php endwhile ?>
                <?php else: ?>
                    <div>There are no default reminders set for this calendar.</div>
                <?php endif ?>
            </div>
            <a href="#" id="event_add_reminder">Add a reminder</a>
            <br />
            <button type="submit" name="edit_calendar_settings" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Settings</button>
            <a class="btn ubirimi-btn" href="/calendar/calendars">Cancel</a>
        </form>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>
</html>