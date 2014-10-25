<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;

?>
<?php if ($historyList): ?>

    <?php foreach($historyData as $date => $data): ?>
        <div>
            <div style="padding-top: 4px; padding-bottom: 4px">
                <div class="headerPageText">What happened on <?php echo $date ?></div>
            </div>
            <table>
                <?php foreach ($data as $userId => $historyData): ?>
                    <tr>
                        <td valign="top">
                            <img width="65px" style="margin-right: 4px" src="<?php echo UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getUserAvatarPicture(array('avatar_picture' => $userData[$userId]['picture'], 'id' => $userId), 'big') ?>" />
                        </td>
                        <td>
                            <?php foreach ($historyData as $dateEvent => $eventDatas): ?>
                                <?php $index++; ?>
                                <?php if ($eventDatas[0]['event'] == 'event_history'): ?>
                                    <?php echo LinkHelper::getUserProfileLink($userId, SystemProduct::SYS_PRODUCT_YONGO, $userData[$userId]['first_name'], $userData[$userId]['last_name']) ?>
                                    updated <a href="/yongo/issue/<?php echo $eventDatas[0]['issue_id'] ?>"><?php echo $eventDatas[0]['code'] . '-' . $eventDatas[0]['nr'] ?></a>
                                    <ul>
                                        <?php foreach ($eventDatas as $event): ?>
                                            <li>
                                                <?php if ($event['new_value'] == 'NULL' || $event['new_value'] == null): ?>
                                                    cleared <?php echo Field::$fieldTranslation[$event['field']] ?>
                                                <?php else: ?>
                                                    updated the <?php if (isset(Field::$fieldTranslation[$event['field']])) echo Field::$fieldTranslation[$event['field']]; else echo $event['field'] ?>
                                                    to <?php echo $event['new_value'] ?>
                                                <?php endif ?>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                <?php endif ?>
                                <?php foreach ($eventDatas as $event): ?>
                                    <?php $index++; ?>
                                    <?php if ($eventDatas[0]['event'] == 'event_created'): ?>

                                        <?php echo LinkHelper::getUserProfileLink($userId, SystemProduct::SYS_PRODUCT_YONGO, $userData[$userId]['first_name'], $userData[$userId]['last_name']) ?>
                                        created <a href="/yongo/issue/<?php echo $eventDatas[0]['issue_id'] ?>"><?php echo $eventDatas[0]['code'] . '-' . $eventDatas[0]['nr'] ?></a>

                                    <?php elseif ($eventDatas[0]['event'] == 'event_commented'): ?>
                                        <?php echo LinkHelper::getUserProfileLink($userId, SystemProduct::SYS_PRODUCT_YONGO, $userData[$userId]['first_name'], $userData[$userId]['last_name']) ?>
                                        commented on <a href="/yongo/issue/<?php echo $eventDatas[0]['issue_id'] ?>"><?php echo $eventDatas[0]['code'] . '-' . $eventDatas[0]['nr'] ?></a>
                                        <br />
                                        <?php echo str_replace("\n", '<br />', $event['comment_content']) ?>
                                    <?php endif ?>

                                <?php endforeach ?>

                                <div><?php echo Util::getFormattedDate($dateEvent, $clientSettings['timezone']) ?> &nbsp; <a href="#" id="add_project_history_comment_<?php echo $eventDatas[0]['issue_id'] . '_' . $index ?>">Comment</a></div>
                                <div id="project_history_comment_<?php echo $eventDatas[0]['issue_id'] ?>_<?php echo $index ?>"></div>
                                <div style="width: 100%; height: 1px; display: block" class="sectionDetail"></div>
                            <?php endforeach ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
    <?php endforeach ?>
    <input type="hidden" value="<?php echo date_format($startDate, 'Y-m-d') ?>" class="activity_last_date" />
    <div class="nextActivityChunk"></div>
<?php else: ?>
    <div style="padding-top: 4px; padding-bottom: 4px"></div>
    <div class="messageGreen">There is no history yet.</div>
<?php endif?>