<?php
    use Ubirimi\QuickNotes\Repository\Note;
    use Ubirimi\QuickNotes\Repository\Notebook;
    use Ubirimi\QuickNotes\Repository\Tag;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $menuSelectedCategory = 'notes';

    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Quick Notes';
    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES);

    $notebooks = Notebook::getByUserId($loggedInUserId, 'array');
    $noteId = $_GET['note_id'];
    $notebookId = $_GET['notebook_id'];
    $notebook = Notebook::getById($notebookId);
    $notes = Notebook::getNotesByNotebookId($notebookId, $loggedInUserId, null, 'array');

    $allTags = Tag::getAll($loggedInUserId);

    if ($noteId != -1) {
        $note = Note::getById($noteId);
        $tags = Note::getTags($noteId);
    } else {
        $note = null;
        $tags = null;
    }

    require_once __DIR__ . '/../../Resources/views/Note/View.php';