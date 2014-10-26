<?php

namespace Ubirimi\QuickNotes\Controller\Note;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\QuickNotes\Repository\Note;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $date = Util::getServerCurrentDateTime();

        $noteId = $request->request->get('id');
        $notebookSelectedId = $request->request->get('notebook_selected_id');

        $note = $this->getRepository(Note::class)->getById($noteId);
        $this->getRepository(Note::class)->deleteById($noteId);

        $notePrevious = $this->getRepository(Note::class)->getPreviousNoteInNotebook($notebookSelectedId, $noteId);
        $noteFollowing = $this->getRepository(Note::class)->getFollowingNoteInNotebook($notebookSelectedId, $noteId);

        if ($notePrevious) {
            return new Response($notebookSelectedId . '/' . $notePrevious['id']);
        } else if ($noteFollowing) {
            return new Response($notebookSelectedId . '/' . $noteFollowing['id']);
        }

        return new Response($note['qn_notebook_id'] . '/' . "-1");
    }
}
