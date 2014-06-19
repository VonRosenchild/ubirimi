<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;

    if (Util::checkUserIsLoggedIn()) {
        $yongoSettings = $session->get('yongo/settings');
    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');

        $yongoSettings = Client::getYongoSettings($clientId);
    }

    $issueId = isset($_POST['issue_id']) ? $_POST['issue_id'] : null;
    $userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $projectId = isset($_POST['project_id']) ? $_POST['project_id'] : null;
    $historyList = Util::getIssueHistory($issueId, $userId, $projectId);
    $color = null;

    $hoursPerDay = $yongoSettings['time_tracking_hours_per_day'];
    $daysPerWeek = $yongoSettings['time_tracking_days_per_week'];
?>

<?php if ($historyList): ?>
    <table class="table table-hover table-condensed">
        <thead>
            <tr>
                <th width="120px"><b>Field</b></th>
                <th><b>Original Value</b></th>
                <th><b>New Value</b></th>
            </tr>
        </thead>

        <?php $old_date = null ?>
        <?php while ($row = $historyList->fetch_array(MYSQLI_ASSOC)): ?>
            <?php if ($old_date != $row['date_created']) : ?>
                <tr>
                    <?php if ($row['source'] == 'history_event'): ?>
                        <td colspan="3">
                            <?php
                                $date_nice = Util::getFormattedDate($row['date_created']);
                                $final_date = $date_nice;
                                if ($date_nice != 'today' && $date_nice != 'yesterday')
                                    $final_date = 'on ' . $final_date;
                            ?>
                            <img src="/img/small_user.png" height="20px" />
                            <?php echo LinkHelper::getUserProfileLink($row['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $row['first_name'], $row['last_name']) ?> made the following changes <?php echo $final_date ?>
                        </td>
                    <?php elseif ($row['source'] == 'comment_event'): ?>
                        <td colspan="3">
                            <img src="/img/small_user.png" height="20px" />
                            <?php echo LinkHelper::getUserProfileLink($row['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $row['first_name'], $row['last_name']) ?> commented on <a href="./issue_detail.php?id=<?php echo $row['issue_id'] ?>"><?php echo $row['code'] . '-' . $row['nr'] ?></a> <?php echo Util::getFormattedDate($row['date_created']) ?>
                        </td>
                    <?php endif ?>
                </tr>
            <?php endif ?>
            <?php if ($row['source'] == 'history_event') : ?>
                <tr>
                    <td width="10%"><?php echo Field::$fieldTranslation[$row['field']] ?></td>
                    <td valign="top" width="45%">
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
            <?php endif ?>
            <?php $old_date = $row['date_created'] ?>
        <?php endwhile ?>
    </table>
<?php else: ?>
    <div>There is no history yet.</div>
<?php endif?>