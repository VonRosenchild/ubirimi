<?php
    use Ubirimi\QuickNotes\Repository\Notebook;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

    $notebookId = $_POST['notebook_id'];

    if ($notebookId == -1) {
        $notebookDefault = Notebook::getDefaultByUserId($loggedInUserId);
        $notebookId = $notebookDefault['id'];
    }

    $noteId = Notebook::addNote($notebookId, $date);

    echo $notebookId . '/' . $noteId;