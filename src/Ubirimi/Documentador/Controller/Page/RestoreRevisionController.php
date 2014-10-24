<?php

namespace Ubirimi\Documentador\Controller\Page;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class RestoreRevisionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $revisionId = $request->request->get('id');
        $pageId = $request->request->get('entity_id');
        $page = $this->getRepository(Entity::class)->getById($pageId);
        $revision = $this->getRepository(Entity::class)->getRevisionsByPageIdAndRevisionId($pageId, $revisionId);

        $date = Util::getServerCurrentDateTime();
        $this->getRepository(Entity::class)->addRevision($pageId, $loggedInUserId, $page['content'], $date);

        $this->getRepository(Entity::class)->updateContent($pageId, $revision['content']);

        return new Response('');
    }
}