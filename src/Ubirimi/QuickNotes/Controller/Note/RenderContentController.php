<?php

namespace Ubirimi\QuickNotes\Controller\Note;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\QuickNotes\Repository\Notebook;
use Ubirimi\QuickNotes\Repository\Tag;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\QuickNotes\Repository\Note;

class RenderContentController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $noteId = $request->request->get('id');
        $note = $this->getRepository(Note::class)->getById($noteId);
        $tags = $this->getRepository(Note::class)->getTags($noteId);
        $notebooks = $this->getRepository(Notebook::class)->getByUserId($session->get('user/id'), 'array');
        $notebook = $this->getRepository(Notebook::class)->getById($note['qn_notebook_id']);
        $allTags = $this->getRepository(Tag::class)->getAll($session->get('user/id'));

        return $this->render(__DIR__ . '/../../Resources/views/Note/RenderContent.php', get_defined_vars());
    }
}
