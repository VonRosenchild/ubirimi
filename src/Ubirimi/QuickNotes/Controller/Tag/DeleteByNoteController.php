<?php
    use Ubirimi\QuickNotes\Repository\Note;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $tagId = $_POST['tag_id'];
    $noteId = $_POST['note_id'];

    Note::deleteTagById($noteId, $tagId);