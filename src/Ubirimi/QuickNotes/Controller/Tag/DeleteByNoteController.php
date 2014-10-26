<?php

namespace Ubirimi\QuickNotes\Controller\Tag;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\QuickNotes\Repository\Note;

class DeleteByNoteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $tagId = $request->request->get('tag_id');
        $noteId = $request->request->get('note_id');

        $this->getRepository(Note::class)->deleteTagById($noteId, $tagId);

        return new Response('');
    }
}
