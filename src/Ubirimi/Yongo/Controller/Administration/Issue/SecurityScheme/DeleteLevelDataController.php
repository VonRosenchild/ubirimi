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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

class DeleteLevelDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueSecuritySchemeLevelDataId = $request->request->get('id');
        $issueSecuritySchemeLevelData = $this->getRepository(IssueSecurityScheme::class)->getLevelDataById($issueSecuritySchemeLevelDataId);
        $issueSecuritySchemeLevelId = $issueSecuritySchemeLevelData['issue_security_scheme_level_id'];
        $issueSecuritySchemeLevel = $this->getRepository(IssueSecurityScheme::class)->getLevelById($issueSecuritySchemeLevelId);

        $this->getRepository(IssueSecurityScheme::class)->deleteLevelDataById($issueSecuritySchemeLevelDataId);

        $date = Util::getServerCurrentDateTime();

        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'UPDATE Yongo Issue Security Scheme Level ' . $issueSecuritySchemeLevel['name'],
            $date
        );

        return new Response('');
    }
}
