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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class FilterController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $filters = array();
        if ($request->request->has('username_filter')) {
            $filters['username'] = $request->request->get('username_filter');
        }

        if ($request->request->has('fullname_filter')) {
            $filters['fullname'] = $request->request->get('fullname_filter');
        }

        if ($request->request->has('group_filter')) {
            $filters['group'] = $request->request->get('group_filter');
        }

        $users = $this->getRepository(UbirimiClient::class)->getUsersByClientIdAndProductIdAndFilters(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $filters
        );

        $menuSelectedCategory = 'user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Users';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/user/_list_user.php', get_defined_vars());
    }
}
