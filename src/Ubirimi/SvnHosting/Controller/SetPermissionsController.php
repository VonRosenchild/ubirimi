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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SetPermissionsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->request->get('id');
        $repoId = Util::cleanRegularInputField($request->request->get('repo_id'));
        $hasRead = Util::cleanRegularInputField($request->request->get('has_read'));
        $hasWrite = Util::cleanRegularInputField($request->request->get('has_write'));

        $this->getRepository(SvnRepository::class)->updateUserPermissions($repoId, $Id, $hasRead, $hasWrite);

        $this->getRepository(SvnRepository::class)->updateHtpasswd($repoId, $session->get('client/company_domain'));
        $this->getRepository(SvnRepository::class)->updateAuthz();

        return new Response('');
    }
}
