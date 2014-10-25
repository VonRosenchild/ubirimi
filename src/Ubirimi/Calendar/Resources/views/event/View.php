<?php

use Ubirimi\Calendar\Repository\Reminder\ReminderPeriod;
use Ubirimi\Util;
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = 'Calendars > <a href="/calendar/view/' . $event['calendar_id'] . '/' . $month . '/' . $year . '">' . $event['calendar_name'] . '</a> > Event > ' . $event['name'];
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="<?php echo $sourcePageLink ?>" class="btn ubirimi-btn">Go Back</a></td>
                <?php if ($myEvent): ?>
                    <td><a href="/calendar/edit/event/<?php echo $eventId ?>?source=<?php echo $sourcePageLink ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnEventDelete" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a></td>
                    <td><a id="btnEventAddGuests" class="btn ubirimi-btn">Add Guests</a></td>
                <?php endif ?>
            </tr>
        </table>

        <table>
            <tr>
                <td>Start time:</td>
                <td><?php echo $event['date_from'] ?></td>
            </tr>
            <tr>
                <td>End time:</td>
                <td><?php echo $event['date_to'] ?></td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><?php echo $event['description'] ?></td>
            </tr>
            <tr>
                <td>Location:</td>
                <td><?php echo $event['location'] ?></td>
            </tr>

            <tr>
                <td valign="top">Reminders:</td>
                <td>
                    <div id="content_event_reminders">
                        <?php while ($eventReminders && $eventReminder = $eventReminders->fetch_array(MYSQLI_ASSOC)): ?>
                            <span>Email</span>
                            <input type="text"
                                   disabled="disabled"
                                   class="inputText"
                                   value="<?php echo $eventReminder['value'] ?>"
                                   name="value_reminder_<?php echo $eventReminder['id'] ?>"
                                   style="width: 50px;" />

                            <span><?php if ($eventReminder['cal_event_reminder_period_id'] == ReminderPeriod::PERIOD_MINUTE) echo 'minutes' ?></span>
                            <span><?php
                                    if ($eventReminder['cal_event_reminder_period_id'] == ReminderPeriod::PERIOD_HOUR) echo 'hours' ?></span>
                            <span><?php
                                    if ($eventReminder['cal_event_reminder_period_id'] == ReminderPeriod::PERIOD_DAY) echo 'days' ?></span>
                            <span><?php if ($eventReminder['cal_event_reminder_period_id'] == ReminderPeriod::PERIOD_WEEK) echo 'weeks' ?></span>
                            <br />
                        <?php endwhile ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Guests:</td>
                <td>
                    <?php if ($guests): ?>
                        <?php while ($guest = $guests->fetch_array(MYSQLI_ASSOC)): ?>
                            <?php echo LinkHelper::getUserProfileLink($guest['id'], SystemProduct::SYS_PRODUCT_YONGO, $guest['first_name'], $guest['last_name']); ?>
                        <?php endwhile ?>
                    <?php else: ?>
                        <div>There are no guests.</div>
                    <?php endif ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="ubirimiModalDialog" id="modalAddGuestsToEvent"></div>
    <div class="ubirimiModalDialog" id="modalDeleteEvent"></div>
    <input type="hidden" value="<?php echo $event['id'] ?>" id="event_id" />
    <input type="hidden" value="<?php echo 'calendar/view/' . $event['calendar_id'] . '/' . $month . '/' . $year ?>" id="calendar_link" />
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>