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
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $hasDocumentadorGlobalAdministrationPermission = $session->get('user/documentator/is_global_administrator');
        $hasDocumentadorGlobalSystemAdministrationPermission = $session->get('user/documentator/is_global_system_administrator');

        if ($hasDocumentadorGlobalAdministrationPermission || $hasDocumentadorGlobalSystemAdministrationPermission) {
            $spaces = $this->getRepository(Space::class)->getByClientId($clientId);
        } else {
            $spaces = $this->getRepository(Space::class)->getWithAdminPermissionByUserId($clientId, $loggedInUserId);
        }
        $clientSettings = $session->get('client/settings');

        $menuSelectedCategory = 'doc_spaces';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/space/List.php', get_defined_vars());
    }
}

