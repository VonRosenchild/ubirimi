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

namespace Ubirimi\Yongo\Controller\Administration\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $filterGroupId = $request->get('group_id');

        if ($filterGroupId) {
            $group = $this->getRepository(UbirimiGroup::class)->getMetadataById($filterGroupId);
            if ($group['client_id'] != $session->get('client/id')) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }
        }

        $users = $this->getRepository(UbirimiClient::class)->getUsers($session->get('client/id'), $filterGroupId, null, 1);

        $menuSelectedCategory = 'user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Users';
        $allGroups = $this->getRepository(UbirimiGroup::class)->getByClientIdAndProductId($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/user/List.php', get_defined_vars());
    }
}
