<?php
    use Ubirimi\Util;
    use Ubirimi\Repository\User\User;
    use Ubirimi\LinkHelper;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\SystemProduct;
?>
<?php if ($historyList): ?>
    <table cellspacing="0" style="table-layout: fixed">

        <?php while ($row = $historyList->fetch_array(MYSQLI_ASSOC)): ?>

            <?php if (substr($date, 0, 10) != substr($row['date_created'], 0, 10)): ?>
                <tr>
                    <td colspan="3"><b><?php echo Util::getFormattedDate($row['date_created']) ?></b></td>
                </tr>
            <?php endif ?>
            <?php if ($date != substr($row['date_created'], 0, 10) || $issueId != $row['issue_id']): ?>
                <?php if ($date): ?>
                    <tr>
                        <td colspan="3">
                            <hr size="1" />
                        </td>
                    </tr>
                <?php endif ?>

                <tr>
                    <td width="50px" valign="top">
                        <img src="<?php echo User::getUserAvatarPicture(array('avatar_picture' => $row['avatar_picture'],'id' => $row['user_id']), 'big') ?>" />
                    </td>
                    <td colspan="2" valing="top">
                        <?php if ($row['event'] == 'event_commented'): ?>
                            <div>

                                <?php echo LinkHelper::getUserProfileLink($row['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $row['first_name'], $row['last_name']) ?> commented on <a href="/yongo/issue/<?php echo $row['issue_id'] ?>"><?php echo $row['code'] . '-' . $row['nr'] ?></a>
                            </div>
                        <?php elseif ($row['event'] == 'event_history'): ?>

                            <?php echo LinkHelper::getUserProfileLink($row['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $row['first_name'], $row['last_name']) ?>
                            updated <a href="/yongo/issue/<?php echo $row['issue_id'] ?>"><?php echo $row['code'] . '-' . $row['nr'] ?></a>

                        <?php elseif ($row['event'] == 'event_created'): ?>
                            <div>

                                <?php echo LinkHelper::getUserProfileLink($row['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $row['first_name'], $row['last_name']) ?>
                                created <a href="/yongo/issue/<?php echo $row['issue_id'] ?>"><?php echo $row['code'] . '-' . $row['nr'] ?></a>
                                at <?php echo date('H:i', strtotime($row['date_created'])) ?>
                            </div>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endif ?>
            <tr>
                <td></td>
                <?php if ($row['event'] == 'event_commented'): ?>
                    <td valign="top" colspan="2" style="word-wrap: break-word;">
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