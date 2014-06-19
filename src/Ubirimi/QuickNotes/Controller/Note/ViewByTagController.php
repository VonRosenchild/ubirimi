<?php
    use Ubirimi\QuickNotes\Repository\Note;
    use Ubirimi\QuickNotes\Repository\Notebook;
    use Ubirimi\QuickNotes\Repository\Tag;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $menuSelectedCategory = 'notebooks';

    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Quick Notes';
    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES);

    $noteId = $_GET['note_id'];
    $tagId = $_GET['tag_id'];

    $note = Note::getById($noteId);
    $notebookId = $note['qn_notebook_id'];
    $notebooks = Notebook::getByUserId($loggedInUserId, 'array');
    $notebook = Notebook::getById($notebookId);
    $notes = Notebook::getNotesByTagId($loggedInUserId, $tagId, 'array');

    $allTags = Tag::getAll($loggedInUserId);
    $tags = Note::getTags($noteId);

    require_once __DIR__ . '/../../Resources/views/Note/View.php';