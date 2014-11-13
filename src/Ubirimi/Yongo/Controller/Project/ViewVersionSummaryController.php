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

namespace Ubirimi\Yongo\Controller\Project;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewVersionSummaryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $loggedInUserId = $session->get('user/id');
            $clientId = $session->get('client/id');
            $clientSettings = $session->get('client/settings');
        } else {
            $clientId = $this->getRepository(UbirimiClient::class)->getClientIdAnonymous();
            $loggedInUserId = null;
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
        }

        $versionId = $request->get('id');
        $version = $this->getRepository(YongoProject::class)->getVersionById($versionId);
        $projectId = $version['project_id'];
        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        if ($project['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $menuSelectedCategory = 'project';

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Version: ' . $version['name'] . ' / Summary';
        $issuesResult = $this->getRepository(Issue::class)->getByParameters(array('project' => $projectId,
            'resolution' => array(-2),
            'page' => 1,
            'version' => array($versionId),
            'issues_per_page' => 10), $loggedInUserId);
        $issues = $issuesResult[0];

        $issuesResultUpdatedRecently = $this->getRepository(Issue::class)->getByParameters(array('project' => $projectId,
            'resolution' => array(-2),
            'page' => 1,
            'issues_per_page' => 10,
            'sort' => 'updated',
            'version' => array($versionId),
            'sort_order' => 'desc'), $loggedInUserId);
        $issuesUpdatedRecently = $issuesResultUpdatedRecently[0];

        return $this->render(__DIR__ . '/../../Resources/views/project/ViewVersionSummary.php', get_defined_vars());
    }
}
