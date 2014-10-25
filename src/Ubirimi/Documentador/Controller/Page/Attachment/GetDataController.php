<?php

namespace Ubirimi\Documentador\Controller\Page\Attachment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Entity\EntityAttachment;
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class GetDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $attachmentId = $request->request->get('id');
        $attachment = $this->getRepository(EntityAttachment::class)->getById($attachmentId);
        $entity = $this->getRepository(Entity::class)->getById($attachment['documentator_entity_id']);
        $spaceId = $entity['space_id'];
        $revisions = $this->getRepository(Entity::class)->getRevisionsByAttachmentId($attachmentId);
        $clientSettings = $session->get('client/settings');

        $index = 0;

        return $this->render(__DIR__ . '/../../../Resources/views/page/attachment/Data.php', get_defined_vars());
    }
}