<?php

namespace Ubirimi\Yongo\Controller\Issue\Comment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueComment;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $commentId = $request->request->get('id');
        $content = $request->request->get('content');

        $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

        IssueComment::updateById($commentId, $content, $loggedInUserId, $date);

        return new Response('');
    }
}
