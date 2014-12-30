<?php
    if (isset($workLog)) {
        $dateStartedWorkLog = \DateTime::createFromFormat('Y-m-d H:i:s', $workLog['date_started']);
        $dateStartedString = date_format($dateStartedWorkLog, 'd-m-Y H:i');
    }
?>

<?php if ($mode == 'delete'): ?>
    <div>Are you sure you want to delete this worklog?</div>
    <div>The Remaining Estimate field allows you to specify how the issues Remaining Estimate should be affected.</div>
<?php endif ?>
<table class="modal-table">
    <?php if ($mode != 'delete'): ?>
        <tr>
            <td width="150px" valign="top">Time Spent <span class="error">*</span></td>
            <td>
                <input type="text"
                       value="<?php if ($workLog) echo $workLog['time_spent'] ?>"
                       required="1"
                       class="inputText"
                       style="width: 100px"
                       id="log_work_time_spent" /> (eg. 3w 4d 12h)
                <div class="error" id="error_time_spent"></div>
            </td>
        </tr>
        <tr>
            <td valign="top">Date started <span class="error">*</span></td>
            <td>
                <input required="1" style="width: 140px" class="inputText" id="log_work_date_started" type="text" value="<?php if ($workLog) echo $dateStartedString; else echo date('d-m-Y h:i') ?>" />
                <div class="error" id="error_date_started"></div>
            </td>
        </tr>
    <?php endif ?>
    <tr>
        <td valign="top">Remaining Estimate</td>
        <td>
            <input checked="checked" type="radio" name="log_work_remaining_estimate" id="radio_log_work_remaining_estimate_auto" value="adjust_automatically" />
            <label for="radio_log_work_remaining_estimate_auto"><span>Adjust automatically</span></label>
            <div class="smallDescription" style="margin-left: 21px">the estimate will be reduced by the amount of work done, but never below 0.</div>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <?php if ($remainingEstimate != '-1'): ?>
                <input type="radio" name="log_work_remaining_estimate" value="existing_estimate" id="radio_log_work_remaining_estimate" />
                <label for="radio_log_work_remaining_estimate"><span>Use existing estimate of <?php echo $remainingEstimate ?></span></label>
            <?php else: ?>
                <input type="radio" name="log_work_remaining_estimate" value="estimate_unset" id="radio_log_work_remaining_estimate" />
                <label for="radio_log_work_remaining_estimate"><span>Leave estimate unset</span></label>
            <?php endif ?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <input type="radio" name="log_work_remaining_estimate" value="set_to" id="radio_log_remaining_work_set_to" />
            <label for="radio_log_remaining_work_set_to"><span>Set to</span></label>
            <input disabled="disabled"
                   type="text"
                   value=""
                   style="width: 80px"
                   class="inputText"
                   id="log_remaining_work_set_to" /> (eg. 3w 4d 12h)
        </td>
    </tr>
    <?php if ($mode == 'new'): ?>
        <tr>
            <td></td>
            <td>
                <input type="radio" name="log_work_remaining_estimate" value="reduce_by" id="radio_log_work_remaining_reduce_by" />
                <label for="radio_log_work_remaining_reduce_by"><span>Reduce by</span></label>
                <input disabled="disabled"
                       type="text"
                       value=""
                       style="width: 80px"
                       class="inputText"
                       id="log_work_remaining_reduce_by" /> (eg. 3w 4d 12h)
            </td>
        </tr>
    <?php elseif ($mode == 'delete'): ?>
        <tr>
            <td></td>
            <td>
                <input type="radio" name="log_work_remaining_estimate" value="increase_by" id="radio_log_work_remaining_increase_by" />
                <label for="radio_log_work_remaining_increase_by"><span>Increase by</span></label>
                <input disabled="disabled"
                       type="text"
                       value=""
                       style="width: 80px"
                       class="inputText"
                       id="log_work_remaining_increase_by" /> (eg. 3w 4d 12h)
            </td>
        </tr>
    <?php endif ?>
    <?php if ($mode != 'delete'): ?>
        <tr>
            <td valign="top">Work Description</td>
            <td>
                <textarea class="inputTextAreaLarge" id="log_work_work_description"><?php echo $workLog['comment'] ?></textarea>
            </td>
        </tr>
    <?php endif ?>
</table>