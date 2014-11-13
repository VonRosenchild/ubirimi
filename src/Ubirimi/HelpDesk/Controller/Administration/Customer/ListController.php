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

namespace Ubirimi\HelpDesk\Controller\Administration\Customer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Organization\Customer;
use Ubirimi\HelpDesk\Repository\Organization\Organization;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $organizationId = $request->query->get('id');

        if ($organizationId) {
            $customers = $this->getRepository(Customer::class)->getByOrganizationId($organizationId);
            $organization = $this->getRepository(Organization::class)->getById($organizationId);
            $breadCrumbTitle = 'Customers > ' . $organization['name'];
        } else {
            $customers = $this->getRepository(UbirimiUser::class)->getByClientId($session->get('client/id'), 1);
            $breadCrumbTitle = 'Customers > All';
        }

        $menuSelectedCategory = 'helpdesk_organizations';

        $sectionPageTitle = $session->get('client/settings/title_name')
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Administration / Customers';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/customer/List.php', get_defined_vars());
    }
}
