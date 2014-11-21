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

namespace Ubirimi\Documentador\Controller\Page;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddSnapshotController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $entityId = $request->request->get('id');
        $entityLastSnapshotId = $request->request->get('entity_last_snapshot_id');
        $newEntityContent = $request->request->get('content');
        $date = Util::getServerCurrentDateTime();

        $entity = $this->getRepository(Entity::class)->getById($entityId);
        $oldEntityContent = $entity['content'];

        $newEntityContent =  preg_replace("/[[:cntrl:]]/", "", $newEntityContent); ;
        $oldEntityContent =  preg_replace("/[[:cntrl:]]/", "", $oldEntityContent); ;

        if (md5($oldEntityContent) != md5($newEntityContent)) {
            $this->getRepository(Entity::class)->deleteAllSnapshotsByEntityIdAndUserId($entityId, $loggedInUserId, $entityLastSnapshotId);
            $this->getRepository(Entity::class)->addSnapshot($entityId, $newEntityContent, $loggedInUserId, $date);

            $now = date('Y-m-d H:i:s');
            $activeSnapshots = $this->getRepository(Entity::class)->getOtherActiveSnapshots($entityId, $loggedInUserId, $now, 'array');

            return new JsonResponse($activeSnapshots);
        } else {
            return new Response('');
        }
    }
}