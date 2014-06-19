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

    $notebookId = -1;
    $notebooks = Notebook::getByUserId($loggedInUserId, 'array');
    $notes = Note::getAllByUserId($loggedInUserId);

    $notebook = null;
    if ($notes) {
        $tags = Note::getTags($notes[0]['id']);
        $noteId = $notes[0]['id'];
        $note = $notes[0];
    } else {
        $note = null;
    }

    $allTags = Tag::getAll($loggedInUserId);
    require_once __DIR__ . '/../../Resources/views/Note/View.php';