<?php

use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;

if ($historyList): ?>
    <table class="table-condensed" width="100%">
        <thead>
        <tr>
            <th align="center" width="280px"><b>Field</b></th>
            <th align="center"><b>Original Value</b></th>
            <th align="center"><b>New Value</b></th>
        </tr>
        </thead>
    </table>
    <?php $oldDate = null ?>
    <?php while ($row = $historyList->fetch_array(MYSQLI_ASSOC)): ?>
        <?php if ($oldDate != $row['date_created']): ?>
            <?php if ($oldDate) echo '</div>'; ?>
            <div class="divLikeTable" style="border-top: 1px solid #DDDDDD">
            <?php if ($row['source'] == 'history_event'): ?>
                <span style="top: 24px; vertical-align: middle; display: table-cell">
                            <img src="/img/small_user.png" height="20px" style="vertical-align: middle" />
                            <span style="vertical-align: bottom;">
                                <?php echo LinkHelper::getUserProfileLink($row['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $row['first_name'], $row['last_name']) ?> made the following changes <?php echo Util::getFormattedDate($row['date_created'], $clientSettings['timezone']) ?>
                            </span>
                        </span>

            <?php elseif ($row['source'] == 'comment_event'): ?>
                <span style="top: 24px; vertical-align: middle; display: table-cell">
                            <img src="/img/small_user.png" height="20px" />
                        </span>
                <?php echo LinkHelper::getUserProfileLink($row['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $row['first_name'], $row['last_name']) ?> commented on <a href="./issue_detail.php?id=<?php echo $row['issue_id'] ?>"><?php echo $row['code'] . '-' . $row['nr'] ?></a> <?php echo Util::getFormattedDate($row['date_created'], $clientSettings['timezone']) ?>
            <?php endif ?>
        <?php endif ?>
        <?php if ($row['source'] == 'history_event') : ?>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="280px"><?php echo Field::$fieldTranslation[$row['field']] ?></td>
                    <td valign="top">
                        <?php if ($row['field'] == 'time_spent' || $row['field'] == 'remaining_estimate' || $row['field'] == 'worklog_time_spent'): ?>
                            <?php echo ($row['old_value'] != 'NULL') ? Util::transformTimeToString(Util::transformLogTimeToMinutes($row['old_value'], $hoursPerDay, $daysPerWeek), $hoursPerDay, $daysPerWeek) : 'None'; ?>
                        <?php else: ?>
                            <?php echo ($row['old_value'] != 'NULL') ? $row['old_value'] : 'None'; ?>
                        <?php endif ?>
                    </td>
                    <td valign="top" width="45%">
                        <?php if ($row['field'] == 'time_spent' || $row['field'] == 'remaining_estimate' || $row['field'] == 'worklog_time_spent'): ?>
                            <?php echo ($row['new_value'] != 'NULL') ? Util::transformTimeToString(Util::transformLogTimeToMinutes($row['new_value'], $hoursPerDay, $daysPerWeek), $hoursPerDay, $daysPerWeek) : 'None'; ?>
                        <?php else: ?>
                            <?php echo ($row['new_value'] != 'NULL') ? $row['new_value'] : 'None'; ?>
                        <?php endif ?>
                    </td>
                </tr>
            </table>
        <?php endif ?>
        <?php $oldDate = $row['date_created'] ?>
    <?php endwhile ?>
    </div>
<?php else: ?>
    <div>There is no history yet.</div>
<?php endif?>