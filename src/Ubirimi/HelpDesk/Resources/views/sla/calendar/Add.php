<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../../../../Yongo/Resources/views/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../../../../Yongo/Resources/views/_menu.php'; ?>

    <div class="pageContent">
        <form name="add_sla_calendar" action="/helpdesk/sla/calendars/add" method="post">
            <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="">SLAs</a> > Create Calendar') ?>
            <table width="100%">
                <tr>
                    <td valign="top" width="200">Name <span class="mandatory">*</span></td>
                    <td>
                        <input id="name" class="inputText" type="text" value="<?php if (isset($name)) echo $name ?>" name="name"/>
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
                        <textarea class="inputTextAreaLarge" name="description"><?php if (isset($description)) echo $description ?></textarea>
                    </td>
                </tr>
                <?php $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') ?>
                <?php for ($k = 0; $k < 7; $k++): ?>
                    <tr>
                        <td><?php echo $days[$k] ?></td>
                        <td>
                            <select class="inputTextCombo" style="width: 50px" name="from_<?php echo $k ?>_hour">
                                <?php for ($i = 0; $i <= 23; $i++): ?>
                                    <option value="<?php if ($i < 10) echo '0' . $i; else echo $i; ?>"><?php if ($i < 10) echo '0' . $i; else echo $i; ?></option>
                                <?php endfor ?>
                            </select>

                            <select class="inputTextCombo" name="from_<?php echo $k ?>_minute" style="width: 50px">
                                <?php for ($i = 0; $i <= 59; $i++): ?>
                                    <option value="<?php if ($i < 10) echo '0' . $i; else echo $i; ?>"><?php if ($i < 10) echo '0' . $i; else echo $i; ?></option>
                                <?php endfor ?>
                            </select>
                            <span>to</span>
                            <select class="inputTextCombo" name="to_<?php echo $k ?>_hour" style="width: 50px">
                                <?php for ($i = 0; $i <= 23; $i++): ?>
                                    <option value="<?php if ($i < 10) echo '0' . $i; else echo $i; ?>"><?php if ($i < 10) echo '0' . $i; else echo $i; ?></option>
                                <?php endfor ?>
                            </select>
                            <select class="inputTextCombo" name="to_<?php echo $k ?>_minute" style="width: 50px">
                                <?php for ($i = 0; $i <= 59; $i++): ?>
                                    <option value="<?php if ($i < 10) echo '0' . $i; else echo $i; ?>"><?php if ($i < 10) echo '0' . $i; else echo $i; ?></option>
                                <?php endfor ?>
                            </select>
                            <input type="checkbox" value="1" name="not_working_day_<?php echo $k ?>" /> Not Working
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
                            <button type="submit" name="confirm_new_calendar" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Calendar</button>
                            <a class="btn ubirimi-btn" href="/helpdesk/sla/calendars">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <?php require_once __DIR__ . '/../../../../../Yongo/Resources/views/_footer.php' ?>
</body>