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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;


class AddSubtaskDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueId = $request->get('issue_id');
        $projectId = $request->get('project_id');

        $projectData = $this->getRepository(YongoProject::class)->getById($projectId);
        $projectId = $projectData['id'];

        $issue_priorities = $this->getRepository(IssueSettings::class)->getAllIssueSettings('priority', $session->get('client/id'));
        $issue_types = $this->getRepository(YongoProject::class)->getSubTasksIssueTypes($projectId);

        $firstIssueType = $issue_types->fetch_array(MYSQLI_ASSOC);
        $issueTypeId = $firstIssueType['id'];
        $issue_types->data_seek(0);

        $screenData = $this->getRepository(YongoProject::class)->getScreenData($projectData, $issueTypeId, SystemOperation::OPERATION_CREATE);
        $projectComponents = $this->getRepository(YongoProject::class)->getComponents($projectId);
        $projectVersions = $this->getRepository(YongoProject::class)->getVersions($projectId);

        $assignableUsers = $this->getRepository(YongoProject::class)->getUsersWithPermission($projectId, Permission::PERM_ASSIGNABLE_USER);
        $reporterUsers = $this->getRepository(YongoProject::class)->getUsersWithPermission($projectId, Permission::PERM_CREATE_ISSUE);

        $userHasModifyReporterPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_MODIFY_REPORTER, $session->get('user/id'));
        $userHasAssignIssuePermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_ASSIGN_ISSUE, $session->get('user/id'));

        $typeId = null;

        return $this->render(__DIR__ . '/../../Resources/views/issue/render_create_subtask.php', get_defined_vars());
    }
}
