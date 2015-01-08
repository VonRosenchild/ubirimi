<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

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

        $this->getLogger()->addInfo('DELETE Documentador entity attachment ' . $attachment['name'], $this->getLoggerContext());

        $attachments = $this->getRepository(EntityAttachment::class)->getByEntityId($entityId);
        if (!$attachments) {
            // delete the attachment folder
            $attachmentsFilePath = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'attachments');
            Util::deleteDir($attachmentsFilePath . $spaceId . '/' . $entityId);
        }

        return new Response('');
    }
}