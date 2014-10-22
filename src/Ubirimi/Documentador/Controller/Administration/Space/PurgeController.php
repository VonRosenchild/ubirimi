<?php

namespace Ubirimi\Documentador\Controller\Administration\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

        $entity = $this->getRepository('documentador.entity.entity')->getById($entityId);

        $this->getRepository('documentador.entity.comment')->deleteCommentsByEntityId($entityId);
        $this->getRepository('documentador.entity.entity')->removeAsFavouriteForUsers($entityId);
        $this->getRepository('documentador.entity.entity')->deleteRevisionsByEntityId($entityId);
        $this->getRepository('documentador.entity.entity')->deleteFilesByEntityId($entityId);

        $this->getRepository('documentador.entity.attachment')->deleteByEntityId($entityId, $entity['space_id']);
        $this->getRepository('documentador.entity.entity')->deleteById($entityId);

        $date = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'DELETE Documentador entity ' . $entity['name'], $date);
    }
}