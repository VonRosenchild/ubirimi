<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

?>
<?php if (!$comments): ?>
    <div>There are no comments added.</div>
<?php endif ?>

<?php if ($comments): ?>
    <?php $first = true; ?>
    <table width="100%" id="content_list" style="border-top: none; padding: 0px !important;" class="table table-hover">

        <?php while ($comment = $comments->fetch_array(MYSQLI_ASSOC)): ?>
            <tr>
                <td align="left" width="33px" valign="top" <?php if ($first) echo 'style="border-top: none; padding-right: 0px;"' ?>>
                    <img style="height: 33px;" src="<?php echo UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getUserAvatarPicture(array('avatar_picture' => $comment['avatar_picture'], 'id' => $comment['user_id']), 'small') ?>" />
                </td>
                <td valign="top" <?php if ($first) echo 'style="border-top: none;"' ?>>
                    <span>
                        <?php echo LinkHelper::getUserProfileLink($comment['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $comment['first_name'], $comment['last_name']) ?> added a comment on <?php echo Util::getFormattedDate($comment['date_created'], $clientSettings['timezone']) ?>
                    </span>
                    <br />
                    <span><?php echo str_replace("\n", "<br />", htmlentities($comment['content'])) ?></span>
                </td>
                <?php if ($actionButtonsFlag): ?>
                    <td style="text-align: right; <?php if ($first) echo 'border-top: none;' ?>" width="160px;">
                        <?php if ($hasEditAllComments || ($hasEditOwnComments && $loggedInUserId == $comment['user_id'])): ?>
                            <a id="edit_comment_<?php echo $comment['id'] ?>" class="btn ubirimi-btn" href="#"><i class="icon-edit"></i> Edit</a>
                        <?php endif ?>
                        <?php if ($hasDeleteAllComments || ($hasDeleteOwnComments && $loggedInUserId == $comment['user_id'])): ?>
                            <a id="comment_<?php echo $comment['id'] ?>" class="btn ubirimi-btn" href="#"><i class="icon-remove"></i> Delete</a>
                        <?php endif ?>
                    </td>
                <?php endif ?>
            </tr>
            <?php $first = false ?>
        <?php endwhile ?>
    </table>
<?php endif ?>