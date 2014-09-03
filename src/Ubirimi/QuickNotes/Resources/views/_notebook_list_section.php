<?php use Ubirimi\QuickNotes\Repository\Note; ?>
<div>
    <div style="padding-left: 4px; background-color: #DDDDDD; border-bottom: 1px solid #DDDDDD">
        <table width="100%">
            <tr>
                <td width="80px"><b>Notebooks</b></td>
            </tr>
        </table>
    </div>
    <div style="padding-left: 4px;<?php if ($notebookId == -1) echo 'background-color: #CCCCCC;' ?>">
        <table width="100%">
            <tr>
                <td width="80px"><a href="/quick-notes/note/all">All Notes</a></td>
            </tr>
        </table>
    </div>

    <?php if (!empty($notebooks)): ?>
    <?php foreach ($notebooks as $notebookInList): ?>
        <?php $firstNote = Note::getFirstByNotebookId($notebookInList['id']) ?>
        <div style="padding-left: 4px;<?php if ($notebookInList['id'] == $notebookId) echo 'background-color: #EEEEEE;' ?>">
            <a href="/quick-notes/note/<?php echo $notebookInList['id'] ?>/<?php if ($firstNote) echo $firstNote['id']; else echo '-1' ?>"><?php echo $notebookInList['name'] ?></a>
        </div>
    <?php endforeach ?>
    <?php endif ?>
</div>
<div>
    <div style="padding-left: 4px; border-bottom: 1px solid #DDDDDD; background-color: #DDDDDD">
        <table width="100%">
            <tr>
                <td width="40px">
                    <span><b>Tags</b></span>
                </td>
            </tr>
        </table>
    </div>
    <div style="padding-left: 4px;" id="tagContent">
        <?php require_once __DIR__ . '/_tagList.php' ?>
    </div>
</div>