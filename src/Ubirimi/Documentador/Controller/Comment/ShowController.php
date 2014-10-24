<?php

namespace Ubirimi\Documentador\Controller\Comment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Entity\EntityComment;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ShowController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $pageId = $request->request->get('id');

        $childPages = $this->getRepository(Entity::class)->getChildren($pageId);
        $comments = $this->getRepository(EntityComment::class)->getComments($pageId, 'array');
        $pluralCommentsHTML = '';
        if (count($comments) > 1) {
            $pluralCommentsHTML = 's';
        }

        return $this->render(__DIR__ . '/../../Resources/views/page/viewComment.php', get_defined_vars());
    }
}