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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class RestoreRevisionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $revisionId = $request->request->get('id');
        $pageId = $request->request->get('entity_id');
        $page = $this->getRepository(Entity::class)->getById($pageId);
        $revision = $this->getRepository(Entity::class)->getRevisionsByPageIdAndRevisionId($pageId, $revisionId);

        $date = Util::getServerCurrentDateTime();
        $this->getRepository(Entity::class)->addRevision($pageId, $loggedInUserId, $page['content'], $date);

        $this->getRepository(Entity::class)->updateContent($pageId, $revision['content']);

        return new Response('');
    }
}