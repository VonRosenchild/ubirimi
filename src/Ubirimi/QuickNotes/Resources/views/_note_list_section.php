<?php use Ubirimi\Util; ?>

<div style="padding-left: 4px; background-color: #DDDDDD; border-bottom: 2px solid #c6c6c6; height: 33px;">
    <table width="100%">
        <tr>
            <td width="80px">
                <b><?php if ($notebook) echo $notebook['name']; else echo 'All Notes'; ?></b>
            </td>
        </tr>
    </table>
</div>
<?php if ($notes): ?>
    <?php foreach ($notes as $noteInList): ?>
        <div class="contentNote" style="<?php if ($noteInList['id'] == $noteId) echo 'background-color: #EEEEEE;' ?> padding-left: 4px; border-bottom: 1px solid #DDDDDD">

            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div style="width: 350px; overflow: hidden; height: 20px;">
                            <?php if (isset($tagId)): ?>
                                <b><a id="qn_note_list_summary_<?php echo $noteInList['id'] ?>" href="/quick-notes/tag/<?php echo $viewType ?>/<?php echo $viewType ?>/<?php echo $tagId ?>/<?php echo $noteInList['id'] ?>"><?php echo $noteInList['summary'] ?><a/></b>
                            <?php else: ?>
                                <b><a id="qn_note_list_summary_<?php echo $noteInList['id'] ?>" href="/quick-notes/note/<?php echo $viewType ?>/<?php echo $notebookId ?>/<?php echo $noteInList['id'] ?>"><?php echo $noteInList['summary'] ?><a/></b>
                            <?php endif ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><div><?php echo Util::getFormattedDate($noteInList['date_created']); if ($noteInList['content']) echo ' - ' . substr(strip_tags($noteInList['content']), 0, 70) ?></div></td>
                </tr>
            </table>
            <?php if (isset($tagId)): ?>
                <input type="hidden" value="/quick-notes/tag/<?php echo $viewType ?>/<?php echo $tagId ?>/<?php echo $noteInList['id'] ?>" class="contentNoteLink" />
            <?php else: ?>
                <input type="hidden" value="/quick-notes/note/<?php echo $viewType ?>/<?php echo $notebookId ?>/<?php echo $noteInList['id'] ?>" class="contentNoteLink" />
            <?php endif ?>
        </div>
    <?php endforeach ?>
<?php endif ?>