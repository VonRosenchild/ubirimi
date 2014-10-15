<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class RestoreRevisionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $revisionId = $_POST['id'];
        $pageId = $_POST['entity_id'];
        $page = Entity::getById($pageId);
        $revision = Entity::getRevisionsByPageIdAndRevisionId($pageId, $revisionId);

        $date = Util::getServerCurrentDateTime();
        Entity::addRevision($pageId, $loggedInUserId, $page['content'], $date);

        Entity::updateContent($pageId, $revision['content']);

        return new Response('');
    }
}