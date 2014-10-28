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
        $viewType = $request->get('view_type');
        $menuSelectedCategory = 'notebooks';

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME
            . ' / Quick Notes';

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES);

        $noteId = $request->get('note_id');
        $tagId = $request->get('tag_id');

        if (-1 == $tagId) {
            $tagId = null;
        }

        $note = $this->getRepository(Note::class)->getById($noteId);
        $notebookId = $note['qn_notebook_id'];
        $notebooks = $this->getRepository(Notebook::class)->getByUserId($session->get('user/id'), 'array');
        $notebook = $this->getRepository(Notebook::class)->getById($notebookId);
        $notes = $this->getRepository(Notebook::class)->getNotesByTagId($session->get('user/id'), $tagId, 'array');

        $allTags = $this->getRepository(Tag::class)->getAll($session->get('user/id'));
        $tags = $this->getRepository(Note::class)->getTags($noteId);

        return $this->render(__DIR__ . '/../../Resources/views/Note/View.php', get_defined_vars());
    }
}
