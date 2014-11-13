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

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

        $snapshotId = $request->get('id');
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        $snapshot = $this->getRepository(Entity::class)->getSnapshotById($snapshotId);
        $entityId = $snapshot['documentator_entity_id'];
        $this->getRepository(Entity::class)->updateContent($entityId, $snapshot['content']);
        $this->getRepository(Entity::class)->deleteAllSnapshotsByEntityIdAndUserId($entityId, $loggedInUserId);

        return new RedirectResponse('/documentador/page/edit/' . $entityId);
    }
}

