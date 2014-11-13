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
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Screen\Screen;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class TransitionDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $workflowId = $request->get('id');
        $stepIdFrom = $request->get('step_id_from');
        $stepIdTo = $request->get('step_id_to');

        $projectId = $request->get('project_id');
        $issueId = $request->get('issue_id');
        $assignableUsers = $this->getRepository(YongoProject::class)->getUsersWithPermission($projectId, Permission::PERM_ASSIGNABLE_USER);
        $projectData = $this->getRepository(YongoProject::class)->getById($projectId);
        $issue = $this->getRepository(Issue::class)->getByIdSimple($issueId);
        $workflowData = $this->getRepository(Workflow::class)->getDataByStepIdFromAndStepIdTo($workflowId, $stepIdFrom, $stepIdTo);
        $screenId = $workflowData['screen_id'];

        $allUsers = $this->getRepository(UbirimiUser::class)->getByClientId($session->get('client/id'));
        $screenData = $this->getRepository(Screen::class)->getDataById($screenId);
        $screenMetadata = $this->getRepository(Screen::class)->getMetaDataById($screenId);
        $resolutions = $this->getRepository(IssueSettings::class)->getAllIssueSettings('resolution', $clientId);
        $projectComponents = $this->getRepository(YongoProject::class)->getComponents($projectId);
        $projectVersions = $this->getRepository(YongoProject::class)->getVersions($projectId);
        $htmlOutput = '';
        $htmlOutput .= '<table class="modal-table">';

        $reporterUsers = $this->getRepository(YongoProject::class)->getUsersWithPermission($projectId, Permission::PERM_CREATE_ISSUE);
        $fieldCodeNULL = null;

        $fieldData = $this->getRepository(YongoProject::class)->getFieldInformation($projectData['issue_type_field_configuration_id'], $issue['type_id'], 'array');

        return $this->render(__DIR__ . '/../../Resources/views/issue/TransitionDialog.php', get_defined_vars());

    }
}