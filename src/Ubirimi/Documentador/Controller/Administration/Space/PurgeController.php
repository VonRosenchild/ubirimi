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

namespace Ubirimi\Documentador\Controller\Administration\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Entity\EntityAttachment;
use Ubirimi\Documentador\Repository\Entity\EntityComment;
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

        $this->getRepository(EntityComment::class)->deleteCommentsByEntityId($entityId);
        $this->getRepository(Entity::class)->removeAsFavouriteForUsers($entityId);
        $this->getRepository(Entity::class)->deleteRevisionsByEntityId($entityId);
        $this->getRepository(Entity::class)->deleteFilesByEntityId($entityId);

        $this->getRepository(EntityAttachment::class)->deleteByEntityId($entityId, $entity['space_id']);
        $this->getRepository(Entity::class)->deleteById($entityId);

        $date = Util::getServerCurrentDateTime();
        $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'DELETE Documentador entity ' . $entity['name'], $date);
    }
}