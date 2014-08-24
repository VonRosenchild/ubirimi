<?php
    use Ubirimi\Util;
    use Ubirimi\Repository\User\User;
    use Ubirimi\LinkHelper;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\SystemProduct;
?>
<?php if ($historyList): ?>

    <?php foreach($historyData as $date => $data): ?>
        <div style="padding-top: 4px; padding-bottom: 4px">
            <div class="headerPageText">What happened on <?php echo $date ?></div>
        </div>
        <table>
            <?php foreach ($data as $userId => $historyData): ?>

                <tr>
                    <td valign="top">
                        <img width="150px;" style="margin-right: 4px" src="<?php echo User::getUserAvatarPicture(array('avatar_picture' => $userData[$userId]['picture'], 'id' => $userId, 'big')) ?>" />
                    </td>
                    <td>
                        <?php $index = 0; ?>
                        <?php foreach ($historyData as $dateEvent => $eventDatas): ?>
                            <?php $index++; ?>

                                <?php foreach ($eventDatas as $event): ?>
                                    <?php if ($eventDatas[0]['event'] == 'event_history'): ?>
                                        <?php echo LinkHelper::getUserProfileLink($userId, SystemProduct::SYS_PRODUCT_YONGO, $userData[$userId]['first_name'], $userData[$userId]['last_name']) ?>
                                        updated <a href="/yongo/issue/<?php echo $eventDatas[0]['issue_id'] ?>"><?php echo $eventDatas[0]['code'] . '-' . $eventDatas[0]['nr'] ?></a>

                                        <div>
                                            <?php if ($event['new_value'] == 'NULL' || $event['new_value'] == null): ?>
                                                cleared <?php echo Field::$fieldTranslation[$event['field']] ?>
                                            <?php else: ?>
                                                updated the <?php echo Field::$fieldTranslation[$event['field']] ?>
                                                to <?php echo $event['new_value'] ?>
                                            <?php endif ?>
                                        </div>
                                    <?php elseif ($eventDatas[0]['event'] == 'event_created'): ?>

                                        <?php echo LinkHelper::getUserProfileLink($userId, SystemProduct::SYS_PRODUCT_YONGO, $userData[$userId]['first_name'], $userData[$userId]['last_name']) ?>
                                        created <a href="/yongo/issue/<?php echo $eventDatas[0]['issue_id'] ?>"><?php echo $eventDatas[0]['code'] . '-' . $eventDatas[0]['nr'] ?></a>

                                    <?php elseif ($eventDatas[0]['event'] == 'event_commented'): ?>
                                        <?php echo LinkHelper::getUserProfileLink($userId, SystemProduct::SYS_PRODUCT_YONGO, $userData[$userId]['first_name'], $userData[$userId]['last_name']) ?>
                                        commented on <a href="/yongo/issue/<?php echo $eventDatas[0]['issue_id'] ?>"><?php echo $eventDatas[0]['code'] . '-' . $eventDatas[0]['nr'] ?></a>
                                        <br />
                                        <?php echo LinkHelper::getUserProfileLink($userId, SystemProduct::SYS_PRODUCT_YONGO, $userData[$userId]['first_name'], $userData[$userId]['last_name']) ?> commented:
                                        <?php echo str_replace("\n", '<br />', $event['comment_content']) ?>
                                    <?php endif ?>

                                <?php endforeach ?>

                            <div><?php echo Util::getFormattedDate($dateEvent) ?> &nbsp; <a href="#">Comment</a></div>
                            <div style="width: 100%; height: 1px; display: block" class="sectionDetail"></div>
                        <?php endforeach ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endforeach ?>
<?php else: ?>
    <div class="messageGreen">There is no history yet.</div>
<?php endif?>