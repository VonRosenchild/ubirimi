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

namespace Ubirimi\Documentador\Controller\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {

            $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
            $clientId = $session->get('client/id');
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;

            $settingsDocumentador = $this->getRepository(UbirimiClient::class)->getDocumentadorSettings($clientId);

            $documentatorUseAnonymous = $settingsDocumentador['anonymous_use_flag'];
            $documentatorAnonymousViewUserProfiles = $settingsDocumentador['anonymous_view_user_profile_flag'];

            if (!($documentatorUseAnonymous && $documentatorAnonymousViewUserProfiles)) {
                Util::signOutAndRedirect();
                die();
            }
        }

        $userId = $request->get('id');
        $user = $this->getRepository(UbirimiUser::class)->getById($userId);
        if ($user['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $menuSelectedCategory = 'documentator';
        $groups = $this->getRepository(UbirimiGroup::class)->getByUserIdAndProductId($userId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $user['first_name'] . ' ' . $user['last_name'] . ' / Summary';

        return $this->render(__DIR__ . '/../../Resources/views/user/View.php', get_defined_vars());
    }
}