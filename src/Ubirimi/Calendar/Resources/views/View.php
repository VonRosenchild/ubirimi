<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb('Calendars'); ?>
    <div class="pageContent">
        <?php require_once __DIR__ . '/_buttonsBar.php' ?>

        <table width="100%" border="0" cellpadding="0" cellspacing="0">

            <tr>
                <td valign="top" width="200px">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td colspan="2" id="sectDates" class="sectionDetail"><span class="sectionDetailTitle">My Calendars</span></td>
                        </tr>
                        <?php if ($calendars): ?>
                            <?php foreach ($calendars as $calendar): ?>
                            <tr>
                                <td width="24px" valign="middle">
                                    <input <?php if (in_array($calendar['id'], $calendarIds)) echo 'checked="checked"' ?> type="checkbox" value="<?php echo $calendar['id'] ?>" id="select_calendar_<?php echo $calendar['id'] ?>" />
                                </td>
                                <td valign="top" width="250px" align="left">
                                    <span style="width: 28px; background-color: <?php echo $calendar['color'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    <?php echo mb_substr($calendar['name'], 0, 20) ?>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </table>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td colspan="2" id="sectDates" class="sectionDetail"><span class="sectionDetailTitle">Shared with me</span></td>
                        </tr>
                        <?php if ($calendarsSharedWithMe): ?>

                            <?php foreach ($calendarsSharedWithMe as $calendar): ?>
                                <tr>
                                    <td width="24px" valign="middle">
                                        <input <?php if (in_array($calendar['id'], $calendarIds)) echo 'checked="checked"' ?> type="checkbox" value="<?php echo $calendar['id'] ?>" id="select_calendar_<?php echo $calendar['id'] ?>" />
                                    </td>
                                    <td valign="top">
                                        <?php echo $calendar['name'] ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td>
                                    <div>No Calendars</div>
                                </td>
                            </tr>
                        <?php endif ?>
                    </table>
                </td>
                <td width="10px"></td>
                <td>
                    <table cellpadding="0" cellspacing="0" class="calendar">
                        <tr>
                            <td class="calendar-day-head"><?php echo implode('</td><td class="calendar-day-head">', $headings) ?></td>
                        </tr>

                        <?php for ($weekNumber = 1; $weekNumber <= $weeks; $weekNumber++): ?>
                            <tr class="calendar-row">
                                <?php for ($dayInWeek = 1; $dayInWeek <= 7; $dayInWeek++): ?>
                                    <?php $classCurrentDate = ''; ?>
                                    <td class="calendar-day<?php echo ' '. $classCurrentDate ?>" id="calendar_day_<?php echo $dates[($weekNumber -1) * 7 + $dayInWeek - 1] ?>" valign="top">
                                        <?php $dayNr = explode("_", $dates[($weekNumber -1) * 7 + $dayInWeek - 1])[0]; ?>
                                        <div class="day-number"><?php echo $dayNr ?></div>
                                        <?php
                                            if ($dayNr < 10) {
                                                $dayNr = '0' . $dayNr;
                                            }
                                            $month2Digits = explode("_", $dates[($weekNumber -1) * 7 + $dayInWeek - 1])[1];
                                            if ($month2Digits < 10) {
                                                $month2Digits = '0' . $month2Digits;
                                            }
                                            $date = $year . '-' . $month2Digits . '-' . $dayNr;
                                        ?>
                                        <?php if ($calendarEvents): ?>
                                            <br />

                                            <?php
                                                $eventsToDisplay = array();
                                            ?>

                                            <?php foreach ($calendarEvents as $event): ?>
                                                <?php if (substr($event['date_from'], 0, 10) <= $date && substr($event['date_to'], 0, 10) >= $date): ?>
                                                    <?php

                                                        $eventsToDisplay[] = $event;
                                                        if (!array_key_exists($event['id'], $eventData)) {

                                                            $levelsUsed = array();
                                                            foreach ($eventData as $data) {
                                                                if ($data[2] >= substr($event['date_from'], 0, 10)) {
                                                                    $levelsUsed[] = $data[0];
                                                                }
                                                            }
                                                            $index = 0;
                                                            $found = false;
                                                            while (!$found) {
                                                                if (!in_array($index, $levelsUsed)) {
                                                                    $eventData[$event['id']][0] = $index;
                                                                    if ($maxLevel < $index)
                                                                        $maxLevel = $index;

                                                                    $eventData[$event['id']][2] = substr($event['date_to'], 0, 10);
                                                                    $found = true;
                                                                } else {
                                                                    $index++;
                                                                }
                                                            }
                                                        }

                                                        $width = 101;
                                                        if (substr($event['date_to'], 0, 10) == $date || $runningDay == 6) {
                                                            $width = 100;
                                                        }
                                                        $eventData[$event['id']][1] = $width;
                                                    ?>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                            <?php
                                                $index = 0;
                                                $eventsPrinted = 0;
                                                while ($index <= $maxLevel) {
                                                    $found = false;
                                                    foreach ($eventsToDisplay as $key => $event) {
                                                        if ($eventData[$event['id']][0] == $index) {
                                                            $eventsPrinted++;
                                                            $opacity = '';
                                                            if ($event['date_to'] < date('Y-m-d 00:00:00')) {
                                                                $opacity = 'opacity: 0.4;';
                                                            }

                                                            if ($event['color'] == $event['calendar_color']) {
                                                                echo '<div class="cal-event event_' . $event['id'] . '" id="cal_event_' . $event['id'] . '" style="z-index: 10; height: 28px; background-color: ' . $event['color'] . '; margin-top: 2px; width: ' . $eventData[$event['id']][1] . '%">';
                                                            } else {
                                                                echo '<div class="cal-event event_' . $event['id'] . '" id="cal_event_' . $event['id'] . '" style="border-bottom: 4px solid ' . $event['calendar_color'] . '; z-index: 10; height: 24px; background-color: ' . $event['color'] . '; margin-top: 2px; width: ' . $eventData[$event['id']][1] . '%">';
                                                            }
                                                            if (!isset($eventData[$event['id']][3])) {
                                                                echo '<div style="padding-left: 2px; ' . $opacity . 'overflow: hidden; width: 100%; display: block; height: 28px;">' . $event['name'] . '</div>';
                                                            } else {
                                                                echo '&nbsp;';
                                                            }
                                                            echo '</div>';
                                                            echo '<div style="display: none" class="cal-event-content">';
                                                            echo '<div class="headerPageText"><a href="/calendar/event/' . $event['id'] . '?source=/calendar/view/' . $calendarIdsString . '/' . $month . '/' . $year . '">' . $event['name'] . '</a></div>';
                                                            echo '<div>' . date("l, F Y", strtotime($event['date_from'])) . ' - ' . date('l, F Y', strtotime($event['date_to'])) . '</div>';
                                                            echo '<div>Calendar: ' . $event['calendar_name'] . '</div>';

                                                            echo '<div>Description: ' . $event['description'] . '</div>';
                                                            echo '<div>Location: ' . $event['location'] . '</div>';
                                                            echo '<hr size="1" />';
                                                            echo '<a href="/calendar/event/' . $event['id'] . '?source=/calendar/view/' . $calendarIdsString . '/' . $month . '/' . $year . '">View Full Event</a>';
                                                            if (in_array($event['calendar_id'], $myCalendarIds)) {
                                                                echo ' | ';
                                                                echo '<a href="/calendar/edit/event/' . $event['id'] . '?source=/calendar/view/' . $calendarIdsString . '/' . $month . '/' . $year . '">Edit</a>';
                                                                echo ' | ';
                                                                if ($event['cal_event_link_id']) {
                                                                    echo '<a href="#" id="event_link_delete_' . $event['id'] . '">Delete</a>';
                                                                } else {
                                                                    echo '<a href="#" id="event_delete_' . $event['id'] . '">Delete</a>';
                                                                }
                                                            }

                                                            echo '</div>';
                                                            $eventData[$event['id']][3] = true;
                                                            $found = true;
                                                            break;
                                                        }
                                                    }
                                                    if (!$found && $eventsToDisplay) {
                                                        if ($eventsPrinted <= count($eventsToDisplay) || $eventsPrinted <= $index) {
                                                            echo '<div style="margin-bottom: 2px; margin-top: 3px;">&nbsp;</div>';
                                                        }
                                                        $eventsPrinted++;
                                                    }

                                                    if ($eventsPrinted > 5) {
                                                        if ((count($eventsToDisplay) - $eventsPrinted) > 0) {
                                                            echo '<div class="show-all-events"><a id="show_all_events_' . $date . '" href="#">+' . (count($eventsToDisplay) - $eventsPrinted) . ' more</a></div>';
                                                        }

                                                        break;
                                                    }

                                                    $index++;
                                                }

                                                if (isset($key) && $key != count($eventsToDisplay)) {

                                                    echo '<div id="content_all_events_' . $date . '" style="display: none; background-color: #DDDDDD; border: 2px solid #EEEEEE">';
                                                    echo '<div class="show-all-events"><b>Monday May 12 [<a id="close_content_all_events_' . $date . '" href="#">close</a>]</b></div>';
                                                    for ($pos = 0; $pos < count($eventsToDisplay); $pos++) {
                                                        echo '<div>' . $eventsToDisplay[$pos]['name'] . '</div>';
                                                    }
                                                    echo '</div>';
                                                }
                                            ?>
                                        <?php endif ?>
                                    </td>
                                <?php endfor ?>
                            </tr>
                        <?php endfor ?>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="ubirimiModalDialog" id="modalAddEvent"></div>
    <div class="ubirimiModalDialog" id="modalDeleteRecurringEvent"></div>
    <input type="hidden" value="<?php echo $year ?>" id="cal_current_year" />
    <input type="hidden" value="<?php echo $month ?>" id="cal_current_month" />
    <input type="hidden" id="mouseTracker" />
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>