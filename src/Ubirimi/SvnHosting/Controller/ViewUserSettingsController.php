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

namespace Ubirimi\SvnHosting\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewUserSettingsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $menuSelectedCategory = 'svn';
        $svnRepoId = $request->get('id');
        $svnRepo = $this->getRepository(SvnRepository::class)->getById($svnRepoId);

        if ($svnRepo['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $userData = $this->getRepository(SvnRepository::class)->getUserByRepoIdAndUserId($svnRepoId, $loggedInUserId);
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_SVN_HOSTING_NAME. ' / ' . $svnRepo['code'] . ' / My Settings';

        return $this->render(__DIR__ . '/../Resources/views/ViewUserSettings.php', get_defined_vars());
    }
}
