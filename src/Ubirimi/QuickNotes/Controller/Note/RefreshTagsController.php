<?php

namespace Ubirimi\QuickNotes\Controller\Note;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\QuickNotes\Repository\Tag;

class RefreshTagsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $notebookId = $request->request->get('notebook_id');
        $allTags = Tag::getAll($session->get('user/id'));

        return $this->render(__DIR__ . '/../../Resources/views/Note/RefreshTagsController.php', get_defined_vars());
    }
}
