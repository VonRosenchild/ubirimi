<?php
    use Ubirimi\QuickNotes\Repository\Note;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

    $noteId = $_POST['id'];
    $notebookSelectedId = $_POST['notebook_selected_id'];

    $note = Note::getById($noteId);
    Note::deleteById($noteId);

    $notePrevious = Note::getPreviousNoteInNotebook($notebookSelectedId, $noteId);
    $noteFollowing = Note::getFollowingNoteInNotebook($notebookSelectedId, $noteId);

    if ($notePrevious) {
        echo $notebookSelectedId . '/' . $notePrevious['id'];
    } else if ($noteFollowing) {
        echo $notebookSelectedId . '/' . $noteFollowing['id'];
    } else {
        echo $note['qn_notebook_id'] . '/' . "-1";
    }