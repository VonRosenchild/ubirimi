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
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\CustomField;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueAttachment;
use Ubirimi\Yongo\Repository\Issue\IssueComment;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class SaveIssueTransitionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = UbirimiContainer::get()['session']->get('client/id');
        $loggedInUserId = UbirimiContainer::get()['session']->get('user/id');

        $issueId = $request->request->get('issue_id');

        $fieldTypes = isset($_POST['field_types']) ? $_POST['field_types'] : array();
        $fieldValues = isset($_POST['field_values']) ? $_POST['field_values'] : array();

        $stepIdFrom = $request->request->get('step_id_from');
        $stepIdTo = $request->request->get('step_id_to');
        $workflowId = $request->request->get('workflow_id');
        $attachIdsToBeKept = isset($_POST['attach_ids']) ? $_POST['attach_ids'] : array();
        $attIdsSession = $session->has('added_attachments_in_screen') ? $session->get('added_attachments_in_screen') : array();

        $fieldTypesCustom = $request->request->get('field_types_custom');
        $fieldValuesCustom = $request->request->get('field_values_custom');

        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
        $issueCustomFieldsData = array();

        for ($i = 0; $i < count($fieldTypesCustom); $i++) {
            if ($fieldValuesCustom[$i] != 'null' && $fieldValuesCustom[$i] != '') {
                $issueCustomFieldsData[$fieldTypesCustom[$i]] = $fieldValuesCustom[$i];
            } else {
                $issueCustomFieldsData[$fieldTypesCustom[$i]] = null;
            }
        }

        for ($i = 0; $i < count($attIdsSession); $i++) {
            $attachmentId = $attIdsSession[$i];
            if (!in_array($attachmentId, $attachIdsToBeKept)) {
                $attachment = $this->getRepository(IssueAttachment::class)->getById($attachmentId);
                $this->getRepository(IssueAttachment::class)->deleteById($attachmentId);
                unlink('./../../..' . $attachment['path'] . '/' . $attachment['name']);
            }
        }

        $session->remove('added_attachments_in_screen');
        $issueData = $this->getRepository(Issue::class)->getById($issueId, $loggedInUserId);
        $workflowData = $this->getRepository(Workflow::class)->getDataByStepIdFromAndStepIdTo($workflowId, $stepIdFrom, $stepIdTo);

        // check if the transition can be executed with respect to the transition conditions
        $canBeExecuted = $this->getRepository(Workflow::class)->checkConditionsByTransitionId($workflowData['id'], $loggedInUserId, $issueData);

        if ($canBeExecuted) {
            $currentDate = Util::getServerCurrentDateTime();

            $newIssueSystemFieldsData = array('issue_project_id' => $issueData['issue_project_id']);

            for ($i = 0; $i < count($fieldTypes); $i++) {
                $newIssueSystemFieldsData[$fieldTypes[$i]] = $fieldValues[$i];
            }

            $oldIssueCustomFieldsData = array();
            foreach ($issueCustomFieldsData as $key => $value) {
                $keyData = explode("_", $key);

                $oldIssueCustomFieldsData[$keyData[0]] = $this->getRepository(CustomField::class)->getCustomFieldsDataByFieldId($issueId, $key);
                unset($issueCustomFieldsData[$key]);
                $issueCustomFieldsData[$keyData[0]] = $value;
            }

            $fieldChanges = $this->getRepository(Issue::class)->computeDifference($issueData, $newIssueSystemFieldsData, $oldIssueCustomFieldsData, $issueCustomFieldsData);

            if (in_array(Field::FIELD_COMMENT_CODE, $fieldTypes)) {
                if ($fieldValues[array_search('comment', $fieldTypes)]) {
                    $commentText = $fieldValues[array_search('comment', $fieldTypes)];

                    $this->getRepository(IssueComment::class)->add($issueId, $loggedInUserId, $commentText, $currentDate);
                    $fieldChanges[] = array('comment', $commentText);
                }
            }

            try {
                $this->getRepository(Issue::class)->updateById($issueId, $newIssueSystemFieldsData, $currentDate);

                // save custom fields
                if (count($issueCustomFieldsData)) {
                    $this->getRepository(CustomField::class)->updateCustomFieldsData($issueId, $issueCustomFieldsData, $currentDate);
                }
            } catch (\Exception $e) {

            }

            $smtpSettings = $session->get('client/settings/smtp');
            if ($smtpSettings) {
                Email::$smtpSettings = $smtpSettings;
            }

            $this->getRepository(WorkflowFunction::class)->triggerPostFunctions($clientId, $issueData, $workflowData, $fieldChanges, $loggedInUserId, $currentDate);

            // update the date_updated field
            $this->getRepository(Issue::class)->updateById($issueId, array('date_updated' => $currentDate), $currentDate);

            return new Response('success');
        } else {
            return new Response('can_not_be_executed');
        }
    }
}