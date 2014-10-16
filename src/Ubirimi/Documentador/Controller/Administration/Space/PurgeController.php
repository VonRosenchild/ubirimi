<?php

namespace Ubirimi\Documentador\Controller\Administration\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
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

        $entityId = $_POST['id'];

        $entity = Entity::getById($entityId);

        EntityComment::deleteCommentsByEntityId($entityId);
        Entity::removeAsFavouriteForUsers($entityId);
        Entity::deleteRevisionsByEntityId($entityId);
        Entity::deleteFilesByEntityId($entityId);

        EntityAttachment::deleteByEntityId($entityId, $entity['space_id']);
        Entity::deleteById($entityId);

        $date = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'DELETE Documentador entity ' . $entity['name'], $date);
    }
}