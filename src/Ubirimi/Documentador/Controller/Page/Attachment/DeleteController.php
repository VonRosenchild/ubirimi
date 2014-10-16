<?php

namespace Ubirimi\Documentador\Controller\Page\Attachment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $attachmentId = $_POST['id'];
        $attachment = EntityAttachment::getById($attachmentId);
        $entityId = $attachment['documentator_entity_id'];
        $space = $this->getRepository('documentador.entity.entity')->getById($entityId);
        $spaceId = $space['space_id'];
        $currentDate = Util::getServerCurrentDateTime();

        EntityAttachment::deleteById($spaceId, $entityId, $attachmentId);

        $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'DELETE Documentador entity attachment ' . $attachment['name'], $currentDate);

        $attachments = EntityAttachment::getByEntityId($entityId);
        if (!$attachments) {
            // delete the attachment folder
            $attachmentsFilePath = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'attachments');
            Util::deleteDir($attachmentsFilePath . $spaceId . '/' . $entityId);
        }
    }
}