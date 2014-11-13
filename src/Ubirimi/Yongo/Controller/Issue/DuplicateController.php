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
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class DuplicateController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $issueId = $request->get('issue_id');

        $summary = $request->get('summary');
        $oldIssueData = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => $issueId), $loggedInUserId);
        $project = $this->getRepository(YongoProject::class)->getById($oldIssueData['issue_project_id']);

        $currentDate = Util::getServerCurrentDateTime();
        $issueSystemFields = array('reporter' => $oldIssueData[Field::FIELD_REPORTER_CODE], 'summary' => $summary, 'priority' => $oldIssueData['priority'],
            'assignee' => $oldIssueData['assignee'], 'description' => $oldIssueData['description'], Field::FIELD_DUE_DATE_CODE => $oldIssueData['due_date'],
            'environment' => $oldIssueData['environment'], 'type' => $oldIssueData['type']);

        $issueReturnValues = $this->getRepository(Issue::class)->add($project, $currentDate, $issueSystemFields, $loggedInUserId);
        $issueId = $issueReturnValues[0];

        $components = $this->getRepository(IssueComponent::class)->getByIssueIdAndProjectId($oldIssueData['id'], $oldIssueData['issue_project_id']);
        if ($components) {
            $components_arr = array();
            while ($component = $components->fetch_array(MYSQLI_ASSOC))
                $components_arr[] = $component['project_component_id'];

            $this->getRepository(Issue::class)->addComponentVersion($issueId, $components_arr, 'issue_component');
        }

        $versions = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($oldIssueData['id'], $oldIssueData['issue_project_id'], Issue::ISSUE_AFFECTED_VERSION_FLAG);
        if ($versions) {
            $versions_arr = array();
            while ($version = $versions->fetch_array(MYSQLI_ASSOC))
                $versions_arr[] = $version['project_version_id'];

            $this->getRepository(Issue::class)->addComponentVersion($issueId, $versions_arr, 'issue_version', Issue::ISSUE_AFFECTED_VERSION_FLAG);
        }

        $targets = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId($oldIssueData['id'], $oldIssueData['issue_project_id'], Issue::ISSUE_FIX_VERSION_FLAG);
        if ($targets) {
            $targets_arr = array();
            while ($target = $targets->fetch_array(MYSQLI_ASSOC))
                $targets_arr[] = $target['project_version_id'];

            $this->getRepository(Issue::class)->addComponentVersion($issueId, $targets_arr, 'issue_version', Issue::ISSUE_FIX_VERSION_FLAG);
        }

        return new Response($issueId);
    }
}
