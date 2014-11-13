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

namespace Ubirimi\Documentador\Controller\Administration\GlobalPermissions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'doc_users';
        $documentatorSettings = $this->getRepository(UbirimiClient::class)->getDocumentadorSettings($clientId);
        $session->set('documentator/settings', $documentatorSettings);

        $users = $this->getRepository(UbirimiUser::class)->getByClientId($clientId);
        $groups = $this->getRepository(UbirimiGroup::class)->getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
        $globalsPermissions = $this->getRepository(GlobalPermission::class)->getAllByProductId(SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/globalpermissions/View.php', get_defined_vars());
    }
}