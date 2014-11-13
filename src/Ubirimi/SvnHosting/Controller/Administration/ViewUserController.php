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

namespace Ubirimi\SvnHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewUserController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'svn';
        $svnRepoId = $request->get('id');
        $session->set('selected_svn_repo_id', $svnRepoId);

        $svnRepo = $this->getRepository(SvnRepository::class)->getById($svnRepoId);
        if ($svnRepo['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $svnRepoUserList = $this->getRepository(SvnRepository::class)->getUserList($session->get('selected_svn_repo_id'), 'array');

        $isSVNAdministrator = $session->get('user/svn_administrator_flag');
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);

        return $this->render(__DIR__ . '/../../Resources/views/administration/ViewUser.php', get_defined_vars());
    }
}
