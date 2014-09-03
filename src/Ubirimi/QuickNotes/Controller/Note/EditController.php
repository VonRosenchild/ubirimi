<?php

namespace Ubirimi\QuickNotes\Controller\Note;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\QuickNotes\Repository\Note;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $noteId = $request->request->get('note_id');
        $content = $request->request->get('content');

        $date = Util::getServerCurrentDateTime();

        Note::updateById($noteId, $content, $date);

        return new Response('');
    }
}
