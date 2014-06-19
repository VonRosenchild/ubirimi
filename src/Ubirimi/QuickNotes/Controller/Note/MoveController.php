<?php
    use Ubirimi\QuickNotes\Repository\Note;

    $noteId = $_POST['note_id'];
    $targetNotebookId = $_POST['target_notebook_id'];

    Note::move($noteId, $targetNotebookId);

