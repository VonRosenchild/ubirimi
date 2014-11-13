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