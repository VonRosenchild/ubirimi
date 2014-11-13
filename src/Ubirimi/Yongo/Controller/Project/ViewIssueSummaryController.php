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
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewIssueSummaryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $loggedInUserId = $session->get('user/id');
            $clientId = $session->get('client/id');
            $clientSettings = $session->get('client/settings');
        } else {
            $loggedInUserId = null;
            $clientId = $this->getRepository(UbirimiClient::class)->getClientIdAnonymous();
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
        }

        $projectId = $request->get('id');
        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        if ($project['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $issueQueryParameters = array('project' => array($projectId), 'resolution' => array(-2));
        $issues = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $loggedInUserId, null, $loggedInUserId);

        $count = 0;
        $statsPriority = array();
        $statsType = array();
        $statsStatus = array();
        $statsAssignee = array();

        if ($issues) {
            $count = $issues->num_rows;
            // group them by priority
            while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                if (!isset($statsPriority[$issue['priority']])) {
                    $statsPriority[$issue['priority']] = array($issue['priority_name'] => 0);
                }
                $statsPriority[$issue['priority']][$issue['priority_name']]++;
            }

            // group them by type
            $issues->data_seek(0);
            $statsType = array();
            while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                if (!isset($statsType[$issue['type']])) {
                    $statsType[$issue['type']] = array($issue['type_name'] => 0);
                }
                $statsType[$issue['type']][$issue['type_name']]++;
            }

            // group them by status
            $issues->data_seek(0);
            $statsStatus = array();
            while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                if (!isset($statsStatus[$issue['status']])) {
                    $statsStatus[$issue['status']] = array($issue['status_name'] => 0);
                }
                $statsStatus[$issue['status']][$issue['status_name']]++;
            }

            // group them by assignee
            $issues->data_seek(0);
            $statsAssignee = array();
            while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                if (!isset($statsAssignee[$issue['assignee']])) {
                    $userName = $issue['ua_first_name'] . ' ' . $issue['ua_last_name'];
                    $statsAssignee[$issue['assignee']] = array($userName => 0);
                }
                $userName = $issue['ua_first_name'] . ' ' . $issue['ua_last_name'];
                $statsAssignee[$issue['assignee']][$userName]++;
            }
        }

        $issueQueryParameters = array('project' => array($projectId), 'resolution' => array(-2), 'component' => -1);
        $issues = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $loggedInUserId, null, $loggedInUserId);
        $countUnresolvedWithoutComponent = 0;
        if ($issues)
            $countUnresolvedWithoutComponent = $issues->num_rows;

        $components = $this->getRepository(YongoProject::class)->getComponents($projectId);

        $statsComponent = array();
        while ($components && $component = $components->fetch_array(MYSQLI_ASSOC)) {
            $issueQueryParameters = array('project' => array($projectId), 'resolution' => array(-2), 'component' => $component['id']);
            $issues = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $loggedInUserId, null, $loggedInUserId);

            if ($issues)
                $statsComponent[$component['name']] = array($component['id'], $issues->num_rows);
        }

        $menuSelectedCategory = 'project';

        $hasGlobalAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
        $hasGlobalSystemAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);
        $hasAdministerProjectsPermission = $this->getRepository(UbirimiClient::class)->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_ADMINISTER_PROJECTS);

        $hasAdministerProject = $hasGlobalSystemAdministrationPermission || $hasGlobalAdministrationPermission || $hasAdministerProjectsPermission;

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $project['name'] . ' / Issue Summary';

        return $this->render(__DIR__ . '/../../Resources/views/project/ViewIssuesSummary.php', get_defined_vars());
    }
}
