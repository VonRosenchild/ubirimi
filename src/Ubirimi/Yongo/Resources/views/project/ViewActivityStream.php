<?php
    use Ubirimi\Util;
    use Ubirimi\Repository\User\User;
    use Ubirimi\LinkHelper;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\SystemProduct;
?>
<?php if ($historyList): ?>
    <table width="100%" cellspacing="0" style="table-layout: fixed">

        <?php while ($row = $historyList->fetch_array(MYSQLI_ASSOC)): ?>

            <?php if (substr($date, 0, 10) != substr($row['date_created'], 0, 10)): ?>
                <tr>

                    <td colspan="3"><b><?php echo Util::getFormattedDate($row['date_created']) ?></b></td>
                </tr>
            <?php endif ?>
            <?php if ($date != substr($row['date_created'], 0, 10) || $issueId != $row['issue_id']): ?>
                <tr>
                    <td colspan="3" valing="top">
                        <?php if ($date): ?>
                            <hr size="1" />
                        <?php endif ?>
                        <?php if ($row['event'] == 'event_commented'): ?>
                            <div>
                                <img width="33px" style="vertical-align: middle;" src="<?php echo User::getUserAvatarPicture(array('avatar_picture' => $row['avatar_picture'],'id' => $row['user_id']), 'small') ?>" />
                                <?php echo LinkHelper::getUserProfileLink($row['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $row['first_name'], $row['last_name']) ?> commented on <a href="/yongo/issue/<?php echo $row['issue_id'] ?>"><?php echo $row['code'] . '-' . $row['nr'] ?></a>
                            </div>
                        <?php elseif ($row['event'] == 'event_history'): ?>
                            <img width="33px" style="vertical-align: middle;" src="<?php echo User::getUserAvatarPicture(array('avatar_picture' => $row['avatar_picture'],'id' => $row['user_id']), 'small') ?>" />
                            <?php echo LinkHelper::getUserProfileLink($row['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $row['first_name'], $row['last_name']) ?>
                            updated <a href="/yongo/issue/<?php echo $row['issue_id'] ?>"><?php echo $row['code'] . '-' . $row['nr'] ?></a>

                        <?php elseif ($row['event'] == 'event_created'): ?>
                            <div>
                                <img width="33px" style="vertical-align: middle;" src="<?php echo User::getUserAvatarPicture(array('avatar_picture' => $row['avatar_picture'],'id' => $row['user_id']), 'small') ?>" />
                                <?php echo LinkHelper::getUserProfileLink($row['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $row['first_name'], $row['last_name']) ?>
                                created <a href="/yongo/issue/<?php echo $row['issue_id'] ?>"><?php echo $row['code'] . '-' . $row['nr'] ?></a>
                                at <?php echo date('H:i', strtotime($row['date_created'])) ?>
                            </div>
                        <?php endif ?>

                    </td>
                </tr>
            <?php endif ?>
            <tr>
                <?php if ($row['event'] == 'event_commented'): ?>
                    <td valign="top" colspan="3" style="word-wrap: break-word;">
                        <?php echo LinkHelper::getUserProfileLink($row['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $row['first_name'], $row['last_name']) ?> commented:
                        <?php echo $row['comment_content'] ?>
                    </td>
                <?php elseif ($row['event'] == 'event_history'): ?>
                    <td colspan="3" valign="top">
                        <div>
                            updated the <?php echo Field::$fieldTranslation[$row['field']] ?>
                            to <?php echo $row['new_value'] ?>
                        </div>
                    </td>
                <?php endif ?>
            </tr>
            <?php
            $date = substr($row['date_created'], 0, 10);
            $issueId = $row['issue_id'];
            ?>
        <?php endwhile ?>
    </table>
<?php else: ?>
    <div class="messageGreen">There is no history yet.</div>
<?php endif?>