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

namespace Ubirimi\Yongo\Controller\Administration\GeneralConfiguration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'system';
        $clientSettings = $this->getRepository(UbirimiClient::class)->getYongoSettings($session->get('client/id'));

        if ($request->request->has('update_configuration')) {

            $allowUnassignedIssuesFlag = $request->request->get('allow_unassigned_issues');

            $parameters = array(
                array(
                    'field' => 'allow_unassigned_issues_flag',
                    'value' => $allowUnassignedIssuesFlag,
                    'type' => 'i'
                )
            );

            $this->getRepository(UbirimiClient::class)->updateProductSettings($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO, $parameters);

            $this->getLogger()->addInfo('UPDATE Yongo GeneralSettings Settings', $this->getLoggerContext());

            return new RedirectResponse('/yongo/administration/general-configuration');
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update GeneralSettings Configuration';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/general_configuration/Edit.php', get_defined_vars());
    }
}