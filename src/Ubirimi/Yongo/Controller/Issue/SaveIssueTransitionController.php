<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Attachment;
use Ubirimi\Yongo\Repository\Issue\Comment;
use Ubirimi\Yongo\Repository\Issue\CustomField;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

Util::checkUserIsLoggedInAndRedirect();

$issueId = $_POST['issue_id'];
$fieldTypes = isset($_POST['field_types']) ? $_POST['field_types'] : array();
$fieldValues = isset($_POST['field_values']) ? $_POST['field_values'] : array();

$stepIdFrom = $_POST['step_id_from'];
$stepIdTo = $_POST['step_id_to'];
$workflowId = $_POST['workflow_id'];
$attachIdsToBeKept = isset($_POST['attach_ids']) ? $_POST['attach_ids'] : array();
$attIdsSession = $session->has('added_attachments_in_screen') ? $session->get('added_attachments_in_screen') : array();

$fieldTypesCustom = isset($_POST['field_types_custom']) ? $_POST['field_types_custom'] : null;
$fieldValuesCustom = isset($_POST['field_values_custom']) ? $_POST['field_values_custom'] : null;

$clientSettings = UbirimiContainer::get()['repository']->get('ubirimi.general.client')->getSettings($clientId);
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
        $attachment = Attachment::getById($attachmentId);
        Attachment::deleteById($attachmentId);
        unlink('./../../..' . $attachment['path'] . '/' . $attachment['name']);
    }
}

$session->remove('added_attachments_in_screen');
$issueData = UbirimiContainer::get()['repository']->get('yongo.issue.issue')->getById($issueId, $loggedInUserId);
$workflowData = UbirimiContainer::get()['repository']->get('yongo.workflow.workflow')->getDataByStepIdFromAndStepIdTo($workflowId, $stepIdFrom, $stepIdTo);

// check if the transition can be executed with respect to the transition conditions
$canBeExecuted = UbirimiContainer::get()['repository']->get('yongo.workflow.workflow')->checkConditionsByTransitionId($workflowData['id'], $loggedInUserId, $issueData);

if ($canBeExecuted) {
    $currentDate = Util::getServerCurrentDateTime();

    $newIssueSystemFieldsData = array('issue_project_id' => $issueData['issue_project_id']);

    for ($i = 0; $i < count($fieldTypes); $i++) {
        $newIssueSystemFieldsData[$fieldTypes[$i]] = $fieldValues[$i];
    }

    $oldIssueCustomFieldsData = array();
    foreach ($issueCustomFieldsData as $key => $value) {
        $keyData = explode("_", $key);

        $oldIssueCustomFieldsData[$keyData[0]] = CustomField::getCustomFieldsDataByFieldId($issueId, $key);
        unset($issueCustomFieldsData[$key]);
        $issueCustomFieldsData[$keyData[0]] = $value;
    }

    $fieldChanges = Issue::computeDifference($issueData, $newIssueSystemFieldsData, $oldIssueCustomFieldsData, $issueCustomFieldsData);

    if (in_array(Field::FIELD_COMMENT_CODE, $fieldTypes)) {
        if ($fieldValues[array_search('comment', $fieldTypes)]) {
            $commentText = $fieldValues[array_search('comment', $fieldTypes)];

            UbirimiContainer::get()['repository']->get('yongo.issue.comment')->add($issueId, $loggedInUserId, $commentText, $currentDate);
            $fieldChanges[] = array('comment', $commentText);
        }
    }

    try {
        Issue::updateById($issueId, $newIssueSystemFieldsData, $currentDate);

        // save custom fields
        if (count($issueCustomFieldsData)) {
            CustomField::updateCustomFieldsData($issueId, $issueCustomFieldsData, $currentDate);
        }
    } catch (Exception $e) {

    }

    $smtpSettings = $session->get('client/settings/smtp');
    if ($smtpSettings) {
        Email::$smtpSettings = $smtpSettings;
    }

    WorkflowFunction::triggerPostFunctions($clientId, $issueData, $workflowData, $fieldChanges, $loggedInUserId, $currentDate);

    // update the date_updated field
    Issue::updateById($issueId, array('date_updated' => $currentDate), $currentDate);

    echo 'success';
} else {
    echo 'can_not_be_executed';
}