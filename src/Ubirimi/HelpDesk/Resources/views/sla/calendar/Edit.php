<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../../../Yongo/Resources/views/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../../../../Yongo/Resources/views/_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="">SLAs</a> > Calendars > ' . $calendar['name'] . ' > Edit';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">
        <form name="add_sla_calendar" action="/helpdesk/sla/calendar/edit/<?php echo $calendarId ?>" method="post">
            <table width="100%">
                <tr>
                    <td valign="top" width="200">Name <span class="mandatory">*</span></td>
                    <td>
                        <input id="name" class="inputText" type="text" value="<?php echo $calendar['name'] ?>" name="name"/>
                        <?php if ($emptyName): ?>
                            <br />
                            <div class="error">The name of the calendar can not be empty.</div>
                        <?php elseif ($duplicateName): ?>
                            <br />
                            <div class="error">Duplicate calendar name. Please choose another name.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $calendar['description'] ?></textarea>
                    </td>
                </tr>
                <?php $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') ?>
                <?php for ($k = 1; $k <= 7; $k++): ?>
                    <?php
                        $calendarData = $data->fetch_array(MYSQLI_ASSOC);
                    ?>
                    <tr>
                        <td><?php echo $days[$k - 1] ?></td>
                        <td>
                            <select class="inputTextCombo" style="width: 50px" name="from_<?php echo $k ?>_hour">
                                <?php for ($i = 0; $i <= 23; $i++): ?>
                                    <?php
                                        $value = $i;
                                        if ($i < 10) {
                                            $value = '0' . $i;
                                        }
                                    ?>
                                    <option <?php if (substr($calendarData['time_from'], 0, 2) == $value) echo 'selected="selected"' ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                <?php endfor ?>
                            </select>

                            <select class="inputTextCombo" name="from_<?php echo $k ?>_minute" style="width: 50px">
                                <?php for ($i = 0; $i <= 59; $i++): ?>
                                    <?php
                                        $value = $i;
                                        if ($i < 10) {
                                            $value = '0' . $i;
                                        }
                                    ?>
                                    <option <?php if (substr($calendarData['time_from'], 3, 2) == $value) echo 'selected="selected"' ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                <?php endfor ?>
                            </select>
                            <span>to</span>
                            <select class="inputTextCombo" name="to_<?php echo $k ?>_hour" style="width: 50px">
                                <?php for ($i = 0; $i <= 23; $i++): ?>
                                    <?php
                                        $value = $i;
                                        if ($i < 10) {
                                            $value = '0' . $i;
                                        }
                                    ?>
                                    <option <?php if (substr($calendarData['time_to'], 0, 2) == $value) echo 'selected="selected"' ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                <?php endfor ?>
                            </select>
                            <select class="inputTextCombo" name="to_<?php echo $k ?>_minute" style="width: 50px">
                                <?php for ($i = 0; $i <= 59; $i++): ?>
                                    <?php
                                        $value = $i;
                                        if ($i < 10) {
                                            $value = '0' . $i;
                                        }
                                    ?>
                                    <option <?php if (substr($calendarData['time_to'], 3, 2) == $value) echo 'selected="selected"' ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                <?php endfor ?>
                            </select>
                            <input <?php if ($calendarData['not_working_flag'] == 1) echo 'checked="checked"' ?> type="checkbox" value="1" name="not_working_day_<?php echo $k ?>" /> Not Working
                        </td>
                    </tr>
                <?php endfor ?>
                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="confirm_edit_calendar" class="btn ubirimi-btn">Update Calendar</button>
                            <a class="btn ubirimi-btn" href="/helpdesk/sla/calendar/<?php echo $projectId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <?php require_once __DIR__ . '/../../../../../Yongo/Resources/views/_footer.php' ?>
</body>