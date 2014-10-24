<?php

namespace Ubirimi\Documentador\Controller\Page\Attachment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Entity\EntityAttachment;
use Ubirimi\Repository\General\UbirimiLog;
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

        $attachmentId = $request->request->get('id');
        $attachment = $this->getRepository(EntityAttachment::class)->getById($attachmentId);
        $entityId = $attachment['documentator_entity_id'];
        $space = $this->getRepository(Entity::class)->getById($entityId);
        $spaceId = $space['space_id'];
        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository(EntityAttachment::class)->deleteById($spaceId, $entityId, $attachmentId);

        $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'DELETE Documentador entity attachment ' . $attachment['name'], $currentDate);

        $attachments = $this->getRepository(EntityAttachment::class)->getByEntityId($entityId);
        if (!$attachments) {
            // delete the attachment folder
            $attachmentsFilePath = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'attachments');
            Util::deleteDir($attachmentsFilePath . $spaceId . '/' . $entityId);
        }

        return new Response('');
    }
}