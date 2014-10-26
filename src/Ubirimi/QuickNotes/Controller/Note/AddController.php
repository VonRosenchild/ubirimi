<?php

namespace Ubirimi\QuickNotes\Controller\Note;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\QuickNotes\Repository\Notebook;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $date = Util::getServerCurrentDateTime();

        $notebookId = $request->request->get('notebook_id');

        if (-1 == $notebookId) {
            $notebookDefault = $this->getRepository(Notebook::class)->getDefaultByUserId($session->get('user/id'));
            $notebookId = $notebookDefault['id'];
        }

        $noteId = $this->getRepository(Notebook::class)->addNote($notebookId, $date);

        return new Response($notebookId . '/' . $noteId);
    }
}
