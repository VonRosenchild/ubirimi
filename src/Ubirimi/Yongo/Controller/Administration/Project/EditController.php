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

namespace Ubirimi\Yongo\Controller\Administration\Project;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
use Ubirimi\Yongo\Repository\Project\ProjectCategory;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;


class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $projectId = $request->get('id');
        $leadUsers = $this->getRepository(UbirimiClient::class)->getUsers($session->get('client/id'));

        // todo: leadul sa fie adaugat in lista de useri pentru acest proiect
        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $issueTypeScheme = $this->getRepository(IssueTypeScheme::class)->getByClientId($session->get('client/id'), 'project');
        $issueTypeScreenScheme = $this->getRepository(IssueTypeScreenScheme::class)->getByClientId($session->get('client/id'));
        $workflowScheme = $this->getRepository(WorkflowScheme::class)->getMetaDataByClientId($session->get('client/id'));
        $projectCategories = $this->getRepository(ProjectCategory::class)->getAll($session->get('client/id'));

        $emptyName = false;
        $duplicate_name = false;
        $duplicateCode = false;
        $emptyCode = false;

        if ($request->request->has('confirm_edit_project')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $code = Util::cleanRegularInputField($request->request->get('code'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            $issueTypeSchemeId = $request->request->get('issue_type_scheme');
            $workflowSchemeId = $request->request->get('workflow_scheme');
            $projectCategoryId = $request->request->get('project_category');

            if (-1 == $projectCategoryId) {
                $projectCategoryId = null;
            }

            $leadId = Util::cleanRegularInputField($request->request->get('lead'));

            if (empty($name)) {
                $emptyName = true;
            } else {
                $duplicateProjectByName = $this->getRepository(YongoProject::class)->getByName(mb_strtolower($name), $projectId, $session->get('client/id'));
                if ($duplicateProjectByName) {
                    $duplicate_name = true;
                }
            }

            if (empty($code)) {
                $emptyCode = true;
            } else {
                $project_exists = $this->getRepository(YongoProject::class)->getByCode(mb_strtolower($code), $projectId, $session->get('client/id'));
                if ($project_exists)
                    $duplicateCode = true;
            }

            if (!$emptyName && !$emptyCode && !$duplicate_name && !$duplicateCode) {
                $currentDate = Util::getServerCurrentDateTime();
                $this->getRepository(YongoProject::class)->updateById($projectId, $leadId, $name, $code, $description, $issueTypeSchemeId, $workflowSchemeId, $projectCategoryId, $currentDate);

                $this->getLogger()->addInfo('UPDATE Yongo Project ' . $name, $this->getLoggerContext());

                return new RedirectResponse('/yongo/administration/projects');
            }
        }

        $menuSelectedCategory = 'project';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Project';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/project/Edit.php', get_defined_vars());
    }
}
