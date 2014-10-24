<?php

namespace Ubirimi\Documentador\Controller\Administration\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class PurgeController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $entityId = $request->request->get('id');

        $entity = $this->getRepository(Entity::class)->getById($entityId);

        $this->getRepository('documentador.entity.comment')->deleteCommentsByEntityId($entityId);
        $this->getRepository(Entity::class)->removeAsFavouriteForUsers($entityId);
        $this->getRepository(Entity::class)->deleteRevisionsByEntityId($entityId);
        $this->getRepository(Entity::class)->deleteFilesByEntityId($entityId);

        $this->getRepository('documentador.entity.attachment')->deleteByEntityId($entityId, $entity['space_id']);
        $this->getRepository(Entity::class)->deleteById($entityId);

        $date = Util::getServerCurrentDateTime();
        $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'DELETE Documentador entity ' . $entity['name'], $date);
    }
}