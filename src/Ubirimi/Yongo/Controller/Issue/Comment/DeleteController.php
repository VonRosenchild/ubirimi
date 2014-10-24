<?php

namespace Ubirimi\Yongo\Controller\Issue\Comment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueComment;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $commentId = $request->request->get('id');
        $this->getRepository(IssueComment::class)->deleteById($commentId);

        return new Response('');
    }
}
