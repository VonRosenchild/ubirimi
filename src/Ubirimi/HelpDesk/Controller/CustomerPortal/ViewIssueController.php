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

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\CustomField;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueAttachment;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Issue\Watcher;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class ViewIssueController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $issueId = $request->get('id');

        Util::checkUserIsLoggedInAndRedirect();

        $issue = $this->getRepository(Issue::class)->getById($issueId, $session->get('user/id'));
        $issueId = $issue['id'];
        $projectId = $issue['issue_project_id'];
        $clientSettings = $session->get('client/settings');

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / ' . $issue['project_code'] . '-'
            . $issue['nr'] . ' ' . $issue['summary'];

        $session->set('selected_project_id', $projectId);
        $issueProject = $this->getRepository(YongoProject::class)->getById($projectId);

        /* before going further, check to is if the issue id a valid id -- start */
        $issueValid = true;
        if (!$issue || $session->get('client/id') != $issueProject['client_id']) {
            $issueValid = false;
        }

        /* before going further, check to is if the issue id a valid id -- end */

        $components = $this->getRepository(IssueComponent::class)->getByIssueIdAndProjectId($issueId, $projectId);
        $versionsAffected = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId(
            $issueId,
            $projectId,
            Issue::ISSUE_AFFECTED_VERSION_FLAG
        );

        $versionsTargeted = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId(
            $issueId,
            $projectId,
            Issue::ISSUE_FIX_VERSION_FLAG
        );

        $arrayListResultIds = null;
        if ($session->has('array_ids')) {
            $arrayListResultIds = $session->get('array_ids');
            $index = array_search($issueId, $arrayListResultIds);
        }

        $workflowUsed = $this->getRepository(YongoProject::class)->getWorkflowUsedForType($projectId, $issue[Field::FIELD_ISSUE_TYPE_CODE]);

        $step = $this->getRepository(Workflow::class)->getStepByWorkflowIdAndStatusId($workflowUsed['id'], $issue[Field::FIELD_STATUS_CODE]);
        $stepProperties = $this->getRepository(Workflow::class)->getStepProperties($step['id'], 'array');

        if ($issueValid) {

            $workflowActions = $this->getRepository(Workflow::class)->getTransitionsForStepId($workflowUsed['id'], $step['id']);
            $screenData = $this->getRepository(YongoProject::class)->getScreenData(
                $issueProject,
                $issue[Field::FIELD_ISSUE_TYPE_CODE],
                SystemOperation::OPERATION_CREATE,
                'array'
            );

            $customFieldsData = $this->getRepository(CustomField::class)->getCustomFieldsData($issue['id']);

            $attachments = $this->getRepository(IssueAttachment::class)->getByIssueId($issue['id'], true);
            $countAttachments = count($attachments);

            $atLeastOneSLA = false;
            $slasPrintData = $this->getRepository(Issue::class)->updateSLAValue($issue, $session->get('client/id'), $clientSettings);

            foreach ($slasPrintData as $slaData) {
                if ($slaData['goal']) {
                    $atLeastOneSLA = true;
                    break;
                }
            }
            $watchers = $this->getRepository(Watcher::class)->getByIssueId($issueId);
            $timeTrackingFlag = $session->get('yongo/settings/time_tracking_flag');

            $customFieldsData = $this->getRepository(CustomField::class)->getCustomFieldsData($issue['id']);
            $customFieldsDataUserPickerMultipleUser = $this->getRepository(CustomField::class)->getUserPickerData($issue['id']);
        }

        $menuSelectedCategory = 'issue';
        $hasEditPermission = true;
        $issueEditableProperty = true;
        $hasAssignPermission = false;
        $hasAddCommentsPermission = true;
        $hasDeletePermission = true;
        $childrenIssues = null;
        $linkedIssues = null;
        $linkIssueTypes = null;
        $hasCreateAttachmentPermission = true;
        $hasDeleteAllAttachmentsPermission = false;
        $hasDeleteOwnAttachmentsPermission = true;
        $hasLinkIssuePermission = false;
        $parentIssue = null;

        $menuSelectedCategory = 'home';
        $showWorkflowMenu = false;

        return $this->render(__DIR__ . '/../../Resources/views/customer_portal/ViewIssue.php', get_defined_vars());
    }
}
