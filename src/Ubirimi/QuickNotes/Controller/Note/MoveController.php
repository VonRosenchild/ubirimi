<?php

namespace Ubirimi\QuickNotes\Controller\Note;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\QuickNotes\Repository\Note;

class MoveController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $noteId = $request->request->get('note_id');
        $targetNotebookId = $request->request->get('target_notebook_id');

        $this->getRepository(Note::class)->move($noteId, $targetNotebookId);

        return new Response('');
    }
}
