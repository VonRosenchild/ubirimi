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

namespace Ubirimi\Yongo\Controller\Administration\Issue\Link;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueLinkType;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $sourceLinkTypeId = $request->request->get('id');
        $targetLinkTypeId = $request->request->get('new_id');
        $action = $request->request->get('action');

        if ($action == 'swap') {
            $this->getRepository(IssueLinkType::class)->updateLinkTypeId($sourceLinkTypeId, $targetLinkTypeId);
            $this->getRepository(IssueLinkType::class)->deleteById($sourceLinkTypeId);
        } else if ($action == 'remove' || $action == null) {
            $this->getRepository(IssueLinkType::class)->deleteLinksByLinkTypeId($sourceLinkTypeId);
            $this->getRepository(IssueLinkType::class)->deleteById($sourceLinkTypeId);
        }

        return new Response('');
    }
}
