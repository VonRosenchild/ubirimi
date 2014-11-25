<?php

use Ubirimi\Calendar\Repository\Reminder\ReminderPeriod;
use Ubirimi\Calendar\Repository\Reminder\RepeatCycle;
use Ubirimi\Calendar\Repository\Reminder\ReminderType;
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = 'Calendars > <a href="' . $sourcePageLink . '">' . $event['calendar_name'] . '</a> > Events > ' . $event['name'] . ' > Edit';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <form name="edit_event" action="/calendar/edit/event/<?php echo $eventId ?>?source=<?php echo $sourcePageLink ?>" method="post">
            <table width="100%">
                <tr>
                    <td>Calendar:</td>
                    <td>
                        <select name="calendar" class="select2Input">
                            <?php foreach ($calendars as $calendar): ?>
                                <option <?php if ($event['calendar_id'] == $calendar['id'])
                                    echo 'selected="selected"' ?> value="<?php echo $calendar['id'] ?>"><?php echo $calendar['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td><input type="text" value="<?php echo $event['name'] ?>" name="name" class="inputTextLarge"/></td>
                </tr>
                <tr>
                    <td valign="top">Description:</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $event['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Location:</td>
                    <td>
                        <input type="text" value="<?php echo $event['location'] ?>" name="location" class="inputTextLarge"/>
                    </td>
                </tr>
                <tr>
                    <td valign="top" width="150px">Start time:</td>
                    <td>
                        <input class="inputText"
                               style="width: 120px;"
                               type="text"
                               id="cal_event_edit_date_from"
                               name="date_from"
                               value="<?php echo date('Y-m-d H:i', strtotime($event['date_from'])); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td valign="top">End time:</td>
                    <td>
                        <input class="inputText"
                               style="width: 120px;"
                               name="date_to" id="cal_event_edit_date_to"
                               type="text"
                               value="<?php echo date('Y-m-d H:i', strtotime($event['date_to'])) ?>"/>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Color</td>
                    <td>
                        <input class="inputText color {hash:true}" style="width: 100px" name="color" value="<?php echo $event['color'] ?>"/>
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
                        <select id="add_event_repeat_type" name="add_event_repeat_type" class="select2InputSmall">
                            <option value="-1">Does not repeat</option>
                            <option <?php if (RepeatCycle::REPEAT_DAILY == $defaultEventRepeatCycle) echo 'selected="selected"' ?> value="1">Daily</option>
                            <option <?php if (RepeatCycle::REPEAT_WEEKLY == $defaultEventRepeatCycle) echo 'selected="selected"' ?> value="2">Weekly</option>
                        </select>
                    </td>
                </tr>
                <?php require_once __DIR__ . '/EditRepeatSettings.php' ?>
                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">Reminders</td>
                    <td>
                        <div id="content_event_reminders">

                            <?php while ($eventReminders && $eventReminder = $eventReminders->fetch_array(MYSQLI_ASSOC)): ?>
                                <div id="reminder_content_<?php echo $eventReminder['id'] ?>">
                                    <select name="reminder_type_<?php echo $eventReminder['id'] ?>" class="select2InputSmall">
                                        <option value="<?php
                                            echo ReminderType::REMINDER_EMAIL ?>">Email</option>
                                    </select>
                                    &nbsp;
                                    <input type="text"
                                           value="<?php echo $eventReminder['value'] ?>"
                                           class="inputText"
                                           name="value_reminder_<?php echo $eventReminder['id'] ?>"
                                           style="width: 50px;" />
                                    &nbsp;
                                    <select name="reminder_period_<?php echo $eventReminder['id'] ?>" class="select2InputSmall">
                                        <option <?php
                                            if ($eventReminder['cal_event_reminder_period_id'] == ReminderPeriod::PERIOD_MINUTE) echo 'selected="selected"' ?> value="<?php echo ReminderPeriod::PERIOD_MINUTE ?>">minutes</option>
                                        <option <?php
                                            if ($eventReminder['cal_event_reminder_period_id'] == ReminderPeriod::PERIOD_HOUR) echo 'selected="selected"' ?> value="<?php echo ReminderPeriod::PERIOD_HOUR ?>">hours</option>
                                        <option <?php
                                            if ($eventReminder['cal_event_reminder_period_id'] == ReminderPeriod::PERIOD_DAY) echo 'selected="selected"' ?> value="<?php echo ReminderPeriod::PERIOD_DAY ?>">days</option>
                                        <option <?php
                                            if ($eventReminder['cal_event_reminder_period_id'] == ReminderPeriod::PERIOD_WEEK) echo 'selected="selected"' ?> value="<?php
                                            echo ReminderPeriod::PERIOD_WEEK ?>">weeks</option>
                                    </select>
                                    <img src="/img/delete.png" id="delete_reminder_<?php echo $eventReminder['id'] ?>" title="Delete reminder" />
                                    <br />
                                </div>
                            <?php endwhile ?>
                        </div>
                        <a href="#" id="event_add_reminder">Add a reminder</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1"/>
                    </td>
                </tr>
                <?php if ($repeatDaily || $repeatWeekly): ?>
                    <tr>
                        <td valign="top">
                            <span>Apply this change to</span>
                        </td>
                        <td>
                            <input type="radio" name="change_event" id="event_recurring_this_event" value="this_event" checked="checked">
                            <span>Only this event</span>
                            <br />
                            <div>All other events in the series will remain the same.</div>
                            <input type="radio" name="change_event" value="following_events">
                            <span>Following events</span>
                            <br />
                            <div>This and all the following events will be changed.</div>
                            <div>Any changes to future events will be lost.</div>
                            <input type="radio" name="change_event" id="event_recurring_all_events" value="all_events">
                            <span>All events</span>
                            <br />
                            <div>All events in the series will be changed.</div>
                        </td>
                    </tr>
                <?php endif ?>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_event" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Event</button>
                            <a class="btn ubirimi-btn" href="<?php echo $sourcePageLink ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
            <input type="hidden" value="<?php echo $eventId ?>" id="event_id" />
            <input type="hidden" id="initial_cal_event_edit_date_from" value="<?php echo date('Y-m-d H:i', strtotime($event['date_from'])); ?>"/>
            <input type="hidden" id="initial_cal_event_edit_date_to" value="<?php echo date('Y-m-d H:i', strtotime($event['date_to'])); ?>"/>
        </form>
    </div>

    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>