<?php
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;

?>
<table class="modal-table">
    <tr>
        <td>Author</td>
        <td><?php echo LinkHelper::getUserProfileLink($comment['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $comment['first_name'], $comment['last_name']) ?></td>
    </tr>
    <tr>
        <td>Created</td>
        <td><?php echo date('d F', strtotime($comment['date_created'])) ?></td>
    </tr>
    <tr>
        <td valign="top">Comment</td>
        <td valign="top">
            <textarea id="new_comment_edit_<?php echo $comment['id'] ?>" class="inputTextAreaLarge mousetrap"><?php echo $comment['content'] ?></textarea>
        </td>
    </tr>
</table>