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

class AssociateStep2Controller extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $projectId = $request->get('id');
        $schemeId = $request->get('scheme_id');
        $selectedScheme = $this->getRepository(IssueSecurityScheme::class)->getMetaDataById($schemeId);
        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        $projectIssueSecuritySchemeId = $project['issue_security_scheme_id'];
        $projectIssueSecurityScheme = null;
        if ($projectIssueSecuritySchemeId)
            $projectIssueSecurityScheme = $this->getRepository(IssueSecurityScheme::class)->getMetaDataById($projectIssueSecuritySchemeId);

        $menuSelectedCategory = 'project';
        $selectedSchemeLevels = $this->getRepository(IssueSecurityScheme::class)->getLevelsByIssueSecuritySchemeId($schemeId);

        if ($request->request->has('cancel')) {
            return new RedirectResponse('/yongo/administration/project/issue-security/' . $projectId);
        } elseif ($request->request->has('associate')) {
            $oldNewLevel = array();
            foreach ($request->request as $key => $value) {
                if (substr($key, 0, 10) == 'new_level_') {
                    $newSecurityLevel = $request->request->get($key);
                    $oldSecurityLevel = str_replace('new_level_', '', $key);
                    if ($oldSecurityLevel == 0) {
                        $this->getRepository(YongoProject::class)->updateAllIssuesSecurityLevel($projectId, $newSecurityLevel);
                    } else {
                        $oldNewLevel[] = array($oldSecurityLevel, $newSecurityLevel);
                    }
                } else if ($key == 'no_level_set') {
                    $newSecurityLevel = $request->request->get($key);
                    $this->getRepository(YongoProject::class)->updateIssueSecurityLevelForUnsercuredIssues($projectId, $newSecurityLevel);
                }
            }

            if (count($oldNewLevel)) {
                $date = Util::getServerCurrentDateTime();
                $this->getRepository(YongoProject::class)->updateIssuesSecurityLevel($projectId, $oldNewLevel, $date);
            }

            $this->getRepository(YongoProject::class)->setIssueSecuritySchemeId($projectId, $schemeId);

            return new RedirectResponse('/yongo/administration/project/issue-security/' . $projectId);
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Associate Issue Security';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/AssociateStep2.php', get_defined_vars());
    }
}
