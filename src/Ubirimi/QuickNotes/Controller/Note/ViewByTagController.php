<?php

namespace Ubirimi\QuickNotes\Controller\Note;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\QuickNotes\Repository\Note;
use Ubirimi\QuickNotes\Repository\Notebook;
use Ubirimi\QuickNotes\Repository\Tag;
use Ubirimi\SystemProduct;

class ViewByTagController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $menuSelectedCategory = 'notebooks';

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME
            . ' / Quick Notes';

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES);

        $noteId = $request->get('note_id');
        $tagId = $request->get('tag_id');

        $note = Note::getById($noteId);
        $notebookId = $note['qn_notebook_id'];
        $notebooks = Notebook::getByUserId($session->get('user/id'), 'array');
        $notebook = Notebook::getById($notebookId);
        $notes = Notebook::getNotesByTagId($session->get('user/id'), $tagId, 'array');

        $allTags = Tag::getAll($session->get('user/id'));
        $tags = Note::getTags($noteId);

        return $this->render(__DIR__ . '/../../Resources/views/Note/View.php', get_defined_vars());
    }
}
