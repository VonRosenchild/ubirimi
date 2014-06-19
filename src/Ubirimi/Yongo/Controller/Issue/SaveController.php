<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Agile\Repository\AgileSprint;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueComment;
use Ubirimi\Yongo\Repository\Issue\IssueCustomField;
use Ubirimi\Yongo\Repository\Issue\IssueWatcher;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;

class SaveController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientSettings = Client::getSettings($session->get('client/id'));

        $timeTrackingDefaultUnit = $session->get('yongo/settings/time_tracking_default_unit');

        $projectId = isset($_POST['project_id']) ? $_POST['project_id'] : null;
        $issueId = isset($_POST['issue_id']) ? $_POST['issue_id'] : null;
        $attachIdsToBeKept = $_POST['attach_ids'];

        $fieldTypes = $_POST['field_types'];
        $fieldValues = $_POST['field_values'];

        $fieldTypesCustom = isset($_POST['field_types_custom']) ? $_POST['field_types_custom'] : null;
        $fieldValuesCustom = isset($_POST['field_values_custom']) ? $_POST['field_values_custom'] : null;

        if (!is_array($attachIdsToBeKept))
            $attachIdsToBeKept = array();

        $issueSystemFieldsData = array();
        $issueCustomFieldsData = array();

        for ($i = 0; $i < count($fieldTypes); $i++) {
            if ($fieldValues[$i] != 'null' && $fieldValues[$i] != '')
                $issueSystemFieldsData[$fieldTypes[$i]] = $fieldValues[$i];
            else
                $issueSystemFieldsData[$fieldTypes[$i]] = null;
        }

        for ($i = 0; $i < count($fieldTypesCustom); $i++) {
            if ($fieldValuesCustom[$i] != 'null' && $fieldValuesCustom[$i] != '') {
                $issueCustomFieldsData[$fieldTypesCustom[$i]] = $fieldValuesCustom[$i];
            } else {
                $issueCustomFieldsData[$fieldTypesCustom[$i]] = null;
            }
        }

        if (!$projectId) {
            $projectId = $issueSystemFieldsData['project'];
        }

        $project = Project::getById($projectId);

        $issueTypeId = $issueSystemFieldsData[Field::FIELD_ISSUE_TYPE_CODE];
        $screenData = Project::getScreenData($project, $issueTypeId, SystemOperation::OPERATION_CREATE);

        if (array_key_exists(Field::FIELD_ASSIGNEE_CODE, $issueSystemFieldsData)) {
            // assignee field is placed on screen
            if ($issueSystemFieldsData[Field::FIELD_ASSIGNEE_CODE] == -1)
                $issueSystemFieldsData[Field::FIELD_ASSIGNEE_CODE] = null;
        } else {
            // put the assignee as the project default assignee
            $issueSystemFieldsData[Field::FIELD_ASSIGNEE_CODE] = $project['lead_id'];
        }

        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

        $issueSystemFieldsData['helpdesk_flag'] = 0;
        if ($session->get("selected_product_id") == SystemProduct::SYS_PRODUCT_HELP_DESK) {
            $issueSystemFieldsData['helpdesk_flag'] = 1;
        }
        $issueReturnValues = Issue::add($project,
            $currentDate,
            $issueSystemFieldsData,
            $session->get('user/id'),
            $issueId,
            $timeTrackingDefaultUnit);

        $newIssueId = $issueReturnValues[0];
        $newIssueNumber = $issueReturnValues[1];

        // update last issue number for this project
        Project::updateLastIssueNumber($projectId, $newIssueNumber);

        // if a parent is set check if the parent issue id is part of a sprint. if yes also add the child
        if ($issueId) {
            $sprints = AgileSprint::getByIssueId($session->get('client/id'), $issueId);
            while ($sprints && $sprint = $sprints->fetch_array(MYSQLI_ASSOC)) {
                AgileSprint::addIssues($sprint['id'], array($newIssueId), $session->get('user/id'));
            }
        }

        // check if on the modal there is a comment field
        if (array_key_exists(Field::FIELD_COMMENT_CODE, $issueSystemFieldsData)) {
            $comment = Util::cleanRegularInputField($issueSystemFieldsData[Field::FIELD_COMMENT_CODE]);
            if (!empty($comment)) {
                IssueComment::add($newIssueId, $session->get('user/id'), $comment, $currentDate);
            }
        }

        // save custom fields
        if (count($issueCustomFieldsData))
            IssueCustomField::saveCustomFieldsData($newIssueId, $issueCustomFieldsData, $currentDate);

        if (isset($issueSystemFieldsData['component']))
            Issue::addComponentVersion($newIssueId, $issueSystemFieldsData['component'], 'issue_component');
        if (isset($issueSystemFieldsData['affects_version']))
            Issue::addComponentVersion($newIssueId, $issueSystemFieldsData['affects_version'], 'issue_version', Issue::ISSUE_AFFECTED_VERSION_FLAG);
        if (isset($issueSystemFieldsData['fix_version']))
            Issue::addComponentVersion($newIssueId, $issueSystemFieldsData['fix_version'], 'issue_version', Issue::ISSUE_FIX_VERSION_FLAG);

        Util::manageModalAttachments($newIssueId, $session->get('user/id'), $attachIdsToBeKept);

        $issue = Issue::getById($newIssueId);

        // add the current logged in user to the list of watchers
        IssueWatcher::addWatcher($newIssueId, $session->get('user/id'), $currentDate);

        // add sla information for this issue
        Issue::addPlainSLAData($newIssueId, $projectId);
        $issueSLAData = Issue::updateSLAValue($issue, $session->get('client/id'), $clientSettings);

        $issueEvent = new IssueEvent($issue, $project, IssueEvent::STATUS_NEW);
        $issueLogEvent = new LogEvent(SystemProduct::SYS_PRODUCT_YONGO, 'ADD Yongo issue ' . $project['code'] . '-' . $newIssueNumber);

        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE, $issueEvent);
        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_EMAIL, $issueEvent);
        UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $issueLogEvent);

        if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_YONGO) {
            $response = 'New Issue Created <a href="/yongo/issue/' . $newIssueId . '">' . $project['code'] . '-' . $newIssueNumber . '</a>';
        } else {
            $response = 'New Issue Created <a href="/helpdesk/customer-portal/ticket/' . $newIssueId . '">' . $project['code'] . '-' . $newIssueNumber . '</a>';
        }

        // clean the search information
        $session->remove('array_ids');
        $session->remove('last_search_parameters');

        return new Response($response);
    }
}
