<?php

namespace Ubirimi\QuickNotes\Controller\Note;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\QuickNotes\Repository\Note;
use Ubirimi\QuickNotes\Repository\Notebook;
use Ubirimi\QuickNotes\Repository\Tag;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $menuSelectedCategory = 'notes';

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME
            . ' / Quick Notes';

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES);

        $notebooks = $this->getRepository(Notebook::class)->getByUserId($session->get('user/id'), 'array');
        $noteId = $request->get('note_id');
        $notebookId = $request->get('notebook_id');
        $notebook = $this->getRepository(Notebook::class)->getById($notebookId);
        $notes = $this->getRepository(Notebook::class)->getNotesByNotebookId($notebookId, $session->get('user/id'), null, 'array');

        $allTags = $this->getRepository(Tag::class)->getAll($session->get('user/id'));

        if ($noteId != -1) {
            $note = $this->getRepository(Note::class)->getById($noteId);
            $tags = $this->getRepository(Note::class)->getTags($noteId);
        } else {
            $note = null;
            $tags = null;
        }

        return $this->render(__DIR__ . '/../../Resources/views/Note/View.php', get_defined_vars());
    }
}
