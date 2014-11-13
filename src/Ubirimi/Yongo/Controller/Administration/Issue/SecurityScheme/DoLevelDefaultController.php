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

namespace Ubirimi\Yongo\Controller\Administration\Issue\SecurityScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

class DoLevelDefaultController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $securityLevelId = $request->get('id');
        $securityLevel = $this->getRepository(IssueSecurityScheme::class)->getLevelById($securityLevelId);
        $securityScheme = $this->getRepository(IssueSecurityScheme::class)->getMetaDataById($securityLevel['issue_security_scheme_id']);
        $this->getRepository(IssueSecurityScheme::class)->makeAllLevelsNotDefault($securityScheme['id']);
        $this->getRepository(IssueSecurityScheme::class)->setLevelDefault($securityLevelId);

        return new RedirectResponse('/yongo/administration/issue-security-scheme-levels/' . $securityScheme['id']);
    }
}
