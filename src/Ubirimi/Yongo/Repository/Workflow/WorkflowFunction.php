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

namespace Ubirimi\Yongo\Repository\Workflow;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class WorkflowFunction
{
    const FUNCTION_SET_ISSUE_FIELD_VALUE = 1;
    const FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP = 2;
    const FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY = 3;
    const FUNCTION_CREATE_ISSUE = 4;
    const FUNCTION_FIRE_EVENT = 5;

    public function triggerPostFunctions($clientId, $issueData, $workflowData, $issueFieldChanges, $loggedInUserId, $currentDate) {

        $workflowDataId = $workflowData['id'];
        $issueId = $issueData['id'];
        $projectId = $issueData['issue_project_id'];
        $project = UbirimiContainer::get()['repository']->get(YongoProject::class)->getById($projectId);
        $functions = WorkflowFunction::getByWorkflowDataId($workflowDataId);
        $loggedInUser = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getById($loggedInUserId);

        while ($functions && $function = $functions->fetch_array(MYSQLI_ASSOC)) {

            if ($function['sys_workflow_post_function_id'] == WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY) {
                UbirimiContainer::get()['repository']->get(Issue::class)->updateHistory($issueId, $loggedInUserId, $issueFieldChanges, $currentDate);
                foreach ($issueFieldChanges as $key => $value) {
                    if ($value[0] == Field::FIELD_RESOLUTION_CODE) {
                        if ($value[2]) {
                            UbirimiContainer::get()['repository']->get(Issue::class)->updateById($issueId, array('date_resolved' => $currentDate), $currentDate);
                        } else {
                            // clear the resolved date
                            UbirimiContainer::get()['repository']->get(Issue::class)->updateById($issueId, array('date_resolved' => null), $currentDate);
                        }
                    }
                }
            }

            if ($function['sys_workflow_post_function_id'] == WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP) {

                $finalStatusId = UbirimiContainer::get()['repository']->get(Workflow::class)->getStepById($workflowData['workflow_step_id_to'], 'linked_issue_status_id');

                $finalStatusName = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($finalStatusId, 'status', 'name');
                $initialStatusName = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($issueData[Field::FIELD_STATUS_CODE], 'status', 'name');
                $issueFieldChanges[] = array(Field::FIELD_STATUS_CODE, $initialStatusName, $finalStatusName, $issueData[Field::FIELD_STATUS_CODE], $finalStatusId);
                UbirimiContainer::get()['repository']->get(Issue::class)->updateField($issueId, 'status_id', $finalStatusId);
            }

            if ($function['sys_workflow_post_function_id'] == WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE) {
                $definition_data = $function['definition_data'];
                $data = explode('###', $definition_data);
                $field_name_data = $data[0];
                $field_name_data_arr = explode('=', $field_name_data);
                $field_value = $data[1];
                $field_value_arr = explode('=', $field_value);

                switch ($field_name_data_arr[1]) {
                    case Field::FIELD_RESOLUTION_CODE:

                        if ($field_value_arr[1] == -1) {
                            $updateValue = null;
                        } else {
                            $updateValue = $field_value_arr[1];
                        }

                        UbirimiContainer::get()['repository']->get(Issue::class)->updateById($issueId, array(Field::FIELD_RESOLUTION_CODE => $updateValue), $currentDate);
                        $oldResolution = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($issueData[Field::FIELD_RESOLUTION_CODE], 'resolution', 'name');
                        if ($updateValue)
                            $newResolution = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($updateValue, 'resolution', 'name');
                        else
                            $newResolution = null;

                        if ($oldResolution != $newResolution) {
                            $issueFieldChanges[] = array(Field::FIELD_RESOLUTION_CODE, $oldResolution, $newResolution);
                        }

                        if ($updateValue) {
                            UbirimiContainer::get()['repository']->get(Issue::class)->updateById($issueId, array('date_resolved' => $currentDate), $currentDate);
                        } else {
                            // clear the resolved date
                            UbirimiContainer::get()['repository']->get(Issue::class)->updateById($issueId, array('date_resolved' => null), $currentDate);
                        }
                        break;
                }
            }

            if ($function['sys_workflow_post_function_id'] == WorkflowFunction::FUNCTION_FIRE_EVENT) {

                $definition_data = $function['definition_data'];
                $eventData = explode("=", $definition_data);
                $eventId = $eventData[1];

                $users = UbirimiContainer::get()['repository']->get(YongoProject::class)->getUsersForNotification($projectId, $eventId, $issueData, $loggedInUserId);

                if ($users && Email::$smtpSettings) {
                    while ($user = $users->fetch_array(MYSQLI_ASSOC)) {
                        $sendEmail = true;
                        if ($user['user_id'] == $loggedInUserId && !$user['notify_own_changes_flag']) {
                            $sendEmail = false;
                        }

                        if ($sendEmail) {
                            UbirimiContainer::get()['repository']->get(Email::class)->sendEmailIssueChanged($issueData, $project, $loggedInUser, $clientId, $issueFieldChanges, $user);
                        }
                    }
                }
            }
        }
    }

    public function getFunctionDescription($postFunction) {
        $description = '';
        switch ($postFunction['sys_workflow_post_function_id']) {

            case WorkflowFunction::FUNCTION_SET_ISSUE_STATUS_AS_IN_WORKFLOW_STEP:
            case WorkflowFunction::FUNCTION_UPDATE_ISSUE_CHANGE_HISTORY:
                $description = $postFunction['description'];
                break;

            case WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE:
                $definition_data = $postFunction['definition_data'];
                $params = explode("###", $definition_data);
                $field_name_data = explode("=", $params[0]);
                $field_value_data = explode("=", $params[1]);
                $field_name = $field_name_data[1];
                $field_value = $field_value_data[1];
                $issueField = Field::$fieldTranslation[$field_name];
                if ($field_value == -1) {
                    $description .= 'The <b>' . $issueField . '</b> of the issue will be <b>cleared</b>.';
                } else {
                    switch ($field_name) {

                        case Field::FIELD_RESOLUTION_CODE:
                            $fieldValueData = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($field_value, 'resolution');
                            break;

                        case Field::FIELD_PRIORITY_CODE:
                            $fieldValueData = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($field_value, 'priority');
                            break;
                    }

                    $description .= 'The <b>' . $issueField . '</b> of the issue will be set to <b>' . $fieldValueData['name'] . '</b>.';
                }

                break;

            case WorkflowFunction::FUNCTION_FIRE_EVENT:
                if ($postFunction['definition_data'] == 'create_issue') {
                    $description = 'Create the issue.';
                } else if (strstr($postFunction['definition_data'], '=')) {
                    $definitionData = explode('=', $postFunction['definition_data']);
                    $eventId = $definitionData[1];
                    $event = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getById($eventId);
                    $description = 'Fire a <b>' . $event['name'] . '</b> event that can be processed by the listeners.';
                }

                break;
        }

        return $description;
    }

    public function updateByWorkflowDataIdAndFunctionId($workflowDataId, $functionId, $value) {
        $query = "update workflow_post_function_data set " .
                 "definition_data = ? " .
                 "where workflow_data_id = ? " .
                 "and sys_workflow_post_function_id = ? " .
                 "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sii", $value, $workflowDataId, $functionId);
        $stmt->execute();
    }

    public function getByWorkflowDataId($workflowDataId) {
        $query = "SELECT sys_workflow_post_function.name, workflow_post_function_data.definition_data, sys_workflow_post_function.id as function_id, " .
                    "workflow_post_function_data.sys_workflow_post_function_id, workflow_post_function_data.id, sys_workflow_post_function.description, " .
                    "sys_workflow_post_function.user_editable_flag, sys_workflow_post_function.user_deletable_flag " .
                 "from workflow_post_function_data " .
                 "left join sys_workflow_post_function on sys_workflow_post_function.id = workflow_post_function_data.sys_workflow_post_function_id " .
                 "where workflow_post_function_data.workflow_data_id = ? " .
                 "order by workflow_post_function_data.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $workflowDataId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByWorkflowDataIdAndFunctionId($workflowDataId, $functionId) {
        $query = "SELECT sys_workflow_post_function.name, workflow_post_function_data.definition_data, sys_workflow_post_function.id as function_id, " .
                    "workflow_post_function_data.sys_workflow_post_function_id, workflow_post_function_data.id, sys_workflow_post_function.description, " .
                    "sys_workflow_post_function.user_editable_flag " .
                 "from workflow_post_function_data " .
                 "left join sys_workflow_post_function on sys_workflow_post_function.id = workflow_post_function_data.sys_workflow_post_function_id " .
                 "where workflow_post_function_data.workflow_data_id = ? " .
                 "and workflow_post_function_data.sys_workflow_post_function_id = ? " .
                 "order by workflow_post_function_data.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $workflowDataId, $functionId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getAll() {
        $query = "SELECT * from sys_workflow_post_function";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getById($functionId) {
        $query = "SELECT * from sys_workflow_post_function where id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $functionId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function addPostFunction($workflowDataId, $functionId, $value) {
        $query = "INSERT INTO workflow_post_function_data(workflow_data_id, sys_workflow_post_function_id, definition_data) " .
                 "VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iis", $workflowDataId, $functionId, $value);
        $stmt->execute();
    }

    public function getDataById($workflowPostFunctionDataId) {
        $query = "SELECT sys_workflow_post_function.id as function_id, sys_workflow_post_function.name, workflow_post_function_data.workflow_data_id, workflow_post_function_data.definition_data, " .
                    "workflow.name as workflow_name, workflow.id as workflow_id, workflow_data.transition_name " .
                 "from workflow_post_function_data " .
                 "left join sys_workflow_post_function on sys_workflow_post_function.id = workflow_post_function_data.sys_workflow_post_function_id " .
                 "left join workflow_data on workflow_data.id = workflow_post_function_data.workflow_data_id " .
                 "left join workflow on workflow.id = workflow_data.workflow_id " .
                 "where workflow_post_function_data.id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $workflowPostFunctionDataId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function hasEvent($workflowDataId, $definitionData) {
        $query = "SELECT * from workflow_post_function_data
                    where workflow_data_id = ?
                        and sys_workflow_post_function_id = ?
                        and definition_data = ?
                    limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $functionFireEvent = WorkflowFunction::FUNCTION_FIRE_EVENT;
        $stmt->bind_param("iii", $workflowDataId, $functionFireEvent, $definitionData);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return true;
        else
            return false;
    }

    public function deleteByPostFunctionDataId($postFunctionDataId) {
        $query = "delete from workflow_post_function_data " .
                 "where id = ? " .
                 "limit 1";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $postFunctionDataId);
        $stmt->execute();
    }
}
