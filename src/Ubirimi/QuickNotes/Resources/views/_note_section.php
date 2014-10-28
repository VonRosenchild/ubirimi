<?php if ($note): ?>
    <?php require_once __DIR__ . '/Note/_note_tags_js.php' ?>
    <div style="overflow-y: scroll" id="parentNoteContent">
        <div id="note_content" style="padding-left: 8px"><?php echo $note['content'] ?></div>
    </div>
<?php endif ?>