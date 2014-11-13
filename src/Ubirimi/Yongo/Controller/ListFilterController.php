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

namespace Ubirimi\Yongo\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;

class ListFilterController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $clientSettings = $session->get('client/settings');
            $filters = $this->getRepository(IssueFilter::class)->getAllByUser($session->get('user/id'));
        } else {
            $clientId = $this->getRepository(UbirimiClient::class)->getClientIdAnonymous();
            $loggedInUserId = null;
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
            $filters = $this->getRepository(IssueFilter::class)->getAllByClientId($clientId);
        }

        $loggedInUserId = $session->get('user/id');
        $menuSelectedCategory = 'filters';

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Filters';

        return $this->render(__DIR__ . '/../Resources/views/filter/List.php', get_defined_vars());
    }
}