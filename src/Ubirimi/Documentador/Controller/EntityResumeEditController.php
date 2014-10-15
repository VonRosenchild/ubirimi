<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EntityResumeEditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $source_application = 'documentator';

        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $snapshotId = $_GET['id'];
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        $snapshot = Entity::getSnapshotById($snapshotId);
        $entityId = $snapshot['documentator_entity_id'];
        Entity::updateContent($entityId, $snapshot['content']);
        Entity::deleteAllSnapshotsByEntityIdAndUserId($entityId, $loggedInUserId);

        header('Location: /documentador/page/edit/' . $entityId);
    }
}

