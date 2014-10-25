<?php
use Ubirimi\Util;

$hoursPerDay = $session->get('yongo/settings/time_tracking_hours_per_day');
    $daysPerWeek = $session->get('yongo/settings/time_tracking_days_per_week');
?>
<?php if ($workLogs): ?>
<table class="table table-hover table-condensed">
    <tbody>
    <?php while ($workLogs && $workLog = $workLogs->fetch_array(MYSQLI_ASSOC)): ?>
        <?php
            $editedHTML = '';
            if ($workLog['edited_flag'] == 1) {
                $editedHTML = ' - edited';
            }
        ?>
        <tr>
            <td>
                <div>
                    <?php echo $workLog['first_name'] . ' ' . $workLog['last_name'] . ' logged work - ' . $workLog['date_started'] . $editedHTML ?>
                </div>
                <div>Time Spent: <?php echo Util::transformTimeToString(Util::transformLogTimeToMinutes($workLog['time_spent'], $hoursPerDay, $daysPerWeek), $hoursPerDay, $daysPerWeek); ?></div>
                <?php if ($workLog['comment']): ?>
                <div><?php echo $workLog['comment'] ?></div>
                <?php endif ?>
            </td>
            <td width="20px" align="right">
                <?php if (($workLog['user_id'] == $loggedInUserId && $hasEditOwnWorklogsPermission) || $hasEditAllWorklogsPermission): ?>
                <img style="cursor: pointer" id="edit_work_log_<?php echo $workLog['id'] ?>" title="Edit" height="16px" src="/img/edit.png" />
                <?php endif ?>
            </td>
            <td width="20px" align="right">
                <?php if (($workLog['user_id'] == $loggedInUserId && $hasDeleteOwnWorklogsPermission) || $hasDeleteAllWorklogsPermission): ?>
                <img style="cursor: pointer" id="delete_work_log_<?php echo $workLog['id'] ?>" title="Delete" height="16px" src="/img/delete.png" />
                <?php endif ?>
            </td>
        </tr>
    <?php endwhile ?>
    </tbody>
</table>
<?php else: ?>
    <div>No work has yet been logged on this issue.</div>
<?php endif ?>