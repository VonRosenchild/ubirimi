<?php

namespace Ubirimi\Documentador\Controller\Comment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {

        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $content = Util::cleanRegularInputField($request->request->get('content'));
        $pageId = $request->request->get('entity_id');
        $parentId = $request->request->get('parent_comment_id');
        var_dump($pageId);
        $date = Util::getServerCurrentDateTime();

        $this->getRepository('documentador.entity.comment')->addComment($pageId, $loggedInUserId, $content, $date, $parentId);

        return new Response('');
    }
}