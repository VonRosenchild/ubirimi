<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Email\Email;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueAttachment;
    use Ubirimi\Yongo\Repository\Issue\IssueComment;
    use Ubirimi\Yongo\Repository\Issue\IssueCustomField;
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

    $clientSettings = Client::getSettings($clientId);
    $issueCustomFieldsData = array();

    for ($i = 0; $i < count($fieldTypesCustom); $i++) {
        if ($fieldValuesCustom[$i] != 'null' && $fieldValuesCustom[$i] != '')
            $issueCustomFieldsData[$fieldTypesCustom[$i]] = $fieldValuesCustom[$i];
        else
            $issueCustomFieldsData[$fieldTypesCustom[$i]] = null;
    }

    for ($i = 0; $i < count($attIdsSession); $i++) {
        $attachmentId = $attIdsSession[$i];
        if (!in_array($attachmentId, $attachIdsToBeKept)) {
            $attachment = IssueAttachment::getById($attachmentId);
            IssueAttachment::deleteById($attachmentId);
            unlink('./../../..' . $attachment['path'] . '/' . $attachment['name']);
        }
    }

    $session->remove('added_attachments_in_screen');
    $issueData = Issue::getById($issueId, $loggedInUserId);
    $workflowData = Workflow::getDataByStepIdFromAndStepIdTo($workflowId, $stepIdFrom, $stepIdTo);

    // check if the transition can be executed with respect to the transition conditions
    $canBeExecuted = Workflow::checkConditionsByTransitionId($workflowData['id'], $loggedInUserId, $issueData);

    if ($canBeExecuted) {
        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

        $newIssueSystemFieldsData = array();

        for ($i = 0; $i < count($fieldTypes); $i++) {
            $newIssueSystemFieldsData[$fieldTypes[$i]] = $fieldValues[$i];
        }
        $fieldChanges = Issue::computeDifference($issueData, $newIssueSystemFieldsData);

        if (in_array(Field::FIELD_COMMENT_CODE, $fieldTypes)) {
            if ($fieldValues[array_search('comment', $fieldTypes)]) {
                $commentText = $fieldValues[array_search('comment', $fieldTypes)];

                IssueComment::add($issueId, $loggedInUserId, $commentText, $currentDate);
                $fieldChanges[] = array('comment', $commentText);
            }
        }

        try {
            Issue::updateById($issueId, $newIssueSystemFieldsData, $currentDate);

            // save custom fields
            if (count($issueCustomFieldsData)) {
                IssueCustomField::updateCustomFieldsData($issueId, $issueCustomFieldsData, $currentDate);
            }
        } catch (Exception $e) {

        }

        $smtpSettings = $session->get('client/settings/smtp');
        if ($smtpSettings) {
            Email::$smtpSettings = $smtpSettings;
        }

        WorkflowFunction::triggerPostFunctions($clientId, $issueData, $workflowData, $fieldChanges, $loggedInUserId, $currentDate);

        if (in_array(Field::FIELD_COMMENT_CODE, $fieldTypes)) {
            // update date_resolved date
            Issue::updateById($issueId, array('date_resolved' => $currentDate), $currentDate);
        }

        $issueData = Issue::getById($issueId, $loggedInUserId);
        Issue::updateSLAValue($issueData, $clientId, $clientSettings);

        echo 'success';
    } else
        echo 'can_not_be_executed';