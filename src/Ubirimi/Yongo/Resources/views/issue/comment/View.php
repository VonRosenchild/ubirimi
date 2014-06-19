<?php
    require_once __DIR__ . '/_comments.php';
?>

<table width="100%">
    <?php if ($hasAddCommentsPermission): ?>
        <tr>
            <td>
                <textarea class="inputTextAreaLarge" name="comment" id="comment"></textarea>
            </td>
        </tr>

        <tr>
            <td>
                <input class="btn ubirimi-btn" id="add_comment" type="button" value="Add comment" name="add_comment" />
            </td>
        </tr>
    <?php endif ?>
</table>