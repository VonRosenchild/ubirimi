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

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class IndexController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $signInError = null;

        $httpHOST = Util::getHttpHost();

        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettingsByBaseURL($httpHOST);
        $clientId = $clientSettings['id'];

        $client = $this->getRepository(UbirimiClient::class)->getById($clientId);

        $sectionPageTitle = $client['company_name'] . ' - Welcome to Customer Portal';

        return $this->render(__DIR__ . '/../../Resources/views/customer_portal/Index.php', get_defined_vars());
    }
}
