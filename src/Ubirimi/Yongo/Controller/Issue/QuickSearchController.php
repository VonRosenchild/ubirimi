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

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Permission\Permission;


class QuickSearchController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $searchQuery = $request->request->get('code');

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $projects = $this->getRepository(UbirimiClient::class)->getProjectsByPermission($clientId, $session->get('user/id'), Permission::PERM_BROWSE_PROJECTS, 'array');
        $projects = Util::array_column($projects, 'id');

        // search first for a perfect match
        $issueResult = $this->getRepository(Issue::class)->getByParameters(array('project' => $projects, 'code_nr' => $searchQuery), $loggedInUserId, null, $loggedInUserId);

        if ($issueResult) {
            $issue = $issueResult->fetch_array(MYSQLI_ASSOC);
            return new Response($issue['id']);
        } else {
            return new Response('-1');
        }
    }
}