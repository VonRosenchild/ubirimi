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
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class AssociateStep1Controller extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $projectId = $request->get('id');
        $project = $this->getRepository(YongoProject::class)->getById($projectId);
        $menuSelectedCategory = 'project';
        $issueSecuritySchemes = $this->getRepository(IssueSecurityScheme::class)->getByClientId($session->get('client/id'));

        if ($request->request->has('cancel')) {
            return new RedirectResponse('/yongo/administration/project/issue-security/' . $projectId);
        } elseif ($request->request->has('next')) {
            $schemeId = $request->request->get('scheme');
            return new RedirectResponse('/yongo/administration/project/associate-issue-security-level/' . $projectId . '/' . $schemeId);
        }
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Associate Issue Security';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/AssociateStep1.php', get_defined_vars());
    }
}
