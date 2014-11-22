<?php

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\CustomField;
use Ubirimi\Yongo\Repository\Project\YongoProject;

echo '<table border="0" cellpadding="2" cellspacing="0" id="tableFieldList" class="modal-table">';
    echo '<tr>';
    echo '<td width="170">Project</td>';
    echo '<td>' . $issueData['project_name'] . '</td>';
echo '</tr>';

$fieldCodeNULL = null;
while ($field = $screenData->fetch_array(MYSQLI_ASSOC)) {

    if (!$userHasSetSecurityLevelPermission && $field['field_code'] == Field::FIELD_ISSUE_SECURITY_LEVEL_CODE)
        continue;

    if ($field['field_code'] == Field::FIELD_ISSUE_TIME_TRACKING_CODE) {
        $fieldsPlacedOnScreen[] = $field['field_id'];
        $timeTrackingFieldId = $field['field_id'];
        continue;
    }

    $fieldsPlacedOnScreen[] = $field['field_id'];

    $arrayData = Util::checkKeyAndValueInArray('field_id', $field['field_id'], $fieldData);
    $mandatoryStarHTML = '';
    if ($arrayData['required_flag'])
        $mandatoryStarHTML = '<span class="mandatory">*</span>';

    if ($arrayData && $arrayData['visible_flag']) {
        $requiredHTML = $arrayData['required_flag'] ? 'required="1"' : 'required="0"';

        echo '<tr>';
            echo '<td valign="top">' . $field['field_name'] . ' ' . $mandatoryStarHTML . '</td>';
            echo '<td>';
                switch ($field['field_code']) {

                    case Field::FIELD_ISSUE_TYPE_CODE:
                        echo '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="type" class="select2Input mousetrap">';

                        while ($type = $projectIssueTypes->fetch_array(MYSQLI_ASSOC)) {
                            $selected = '';

                            if ($issueTypeId == $type['id']) $selected = 'selected="selected"';
                            echo '<option ' . $selected . ' value="' . $type['id'] . '">' . $type['name'] . '</option>';
                        }
                        echo '</select>';
                        break;

                    case Field::FIELD_REPORTER_CODE:
                        $textDisabled = '';
                        if (!$userHasModifyReporterPermission)
                            $textDisabled = 'disabled="disabled"';

                        echo '<select ' . $textDisabled . ' ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '" class="select2Input mousetrap">';
                        while ($user = $reporterUsers->fetch_array(MYSQLI_ASSOC)) {
                            $textSelected = '';
                            if ($issueData[Field::FIELD_REPORTER_CODE] == $user['user_id'])
                                $textSelected = 'selected="selected"';

                            echo '<option ' . $textSelected . ' value="' . $user['user_id'] . '">' . $user['first_name'] . ' ' . $user['last_name'] . '</option>';
                        }
                        echo '</select>';

                        break;

                    case Field::FIELD_SUMMARY_CODE:

                        echo '<input ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" class="inputTextLarge mousetrap" type="text" value="' . htmlspecialchars($issueData['summary'], ENT_QUOTES) . '" name="' . $field['field_code'] . '" />';
                        break;
                    case Field::FIELD_ISSUE_SECURITY_LEVEL_CODE:
                        if ($userHasSetSecurityLevelPermission) {
                            echo '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '" class="inputTextCombo">';
                            echo '<option value="-1">None</option>';
                            while ($issueSecuritySchemeLevel = $issueSecuritySchemeLevels->fetch_array(MYSQLI_ASSOC)) {
                                $text = '';
                                if ($issueSecuritySchemeLevel['id'] == $issueData[Field::FIELD_ISSUE_SECURITY_LEVEL_CODE])
                                    $text = 'selected="selected"';

                                echo '<option ' . $text . ' value="' . $issueSecuritySchemeLevel['id'] . '">' . $issueSecuritySchemeLevel['name'] . '</option>';
                            }
                            echo '</select>';
                        }
                        break;

                    case Field::FIELD_PRIORITY_CODE:
                        echo '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '" class="select2Input mousetrap">';
                        while ($priority = $issuePriorities->fetch_array(MYSQLI_ASSOC)) {
                            $text = '';
                            if ($priority['id'] == $issueData[Field::FIELD_PRIORITY_CODE])
                                $text = 'selected="selected"';
                            echo '<option ' . $text . ' value="' . $priority['id'] . '">' . $priority['name'] . '</option>';
                        }
                        echo '</select>';
                        break;

                    case Field::FIELD_ASSIGNEE_CODE:
                        $allowUnassignedIssuesFlag = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getYongoSetting($clientId, 'allow_unassigned_issues_flag');

                        $textDisabled = '';
                        if (!$userHasAssignIssuePermission)
                            $textDisabled = 'disabled="disabled"';

                        echo '<select ' . $textDisabled . ' ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '" class="select2Input mousetrap">';
                        if ($allowUnassignedIssuesFlag) {
                            $textSelected = '';
                            if (!$issueData[Field::FIELD_ASSIGNEE_CODE])
                                $textSelected = 'selected="selected"';
                            echo '<option ' . $textSelected . ' value="-1">No one</option>';
                        }
                        while ($user = $assignableUsers->fetch_array(MYSQLI_ASSOC)) {
                            $textSelected = '';
                            if ($issueData[Field::FIELD_ASSIGNEE_CODE] == $user['user_id'])
                                $textSelected = 'selected="selected"';
                            echo '<option ' . $textSelected . ' value="' . $user['user_id'] . '">' . $user['first_name'] . ' ' . $user['last_name'] . '</option>';
                        }
                        echo '</select>';
                        break;

                    case Field::FIELD_DESCRIPTION_CODE:
                        echo '<textarea ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" class="inputTextAreaLarge mousetrap" name="' . $field['field_code'] . '">' . $issueData['description'] . '</textarea>';
                        break;

                    case Field::FIELD_COMMENT_CODE:
                        echo '<textarea ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" class="inputTextAreaLarge mousetrap" name="' . $field['field_code'] . '"></textarea>';
                        break;

                    case Field::FIELD_DUE_DATE_CODE:
                        $stringDateDue = '';
                        if ($issueData[Field::FIELD_DUE_DATE_CODE])
                            $stringDateDue = date('Y-m-d', strtotime(($issueData[Field::FIELD_DUE_DATE_CODE])));

                        echo '<input style="width: 100px" class="inputText" ' . $requiredHTML . ' value="' . $stringDateDue . '" name="' . $field['field_code'] . '" type="text" value="" id="field_type_' . $field['field_code'] . '" />';

                        break;

                    case Field::FIELD_COMPONENT_CODE:
                        if ($projectComponents) {
                            echo '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '[]" multiple="multiple" class="select2Input mousetrap" style="width: 100%;">';
                            $printedComponents = array();
                            UbirimiContainer::get()['repository']->get(YongoProject::class)->renderTreeComponentsInCombobox($projectComponents, 0, $arrIssueComponents, $printedComponents);
                            echo '</select>';
                        } else {
                            echo '<span>None</span>';
                        }

                        break;

                    case Field::FIELD_AFFECTS_VERSION_CODE:
                        if ($projectVersions) {
                            echo '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '[]" multiple="multiple" class="select2Input mousetrap" style="width: 100%;">';
                            while ($version = $projectVersions->fetch_array(MYSQLI_ASSOC)) {
                                $textSelected = '';
                                if (isset($arrayIssueVersionsAffected) && in_array($version['id'], $arrayIssueVersionsAffected))
                                    $textSelected = 'selected="selected"';
                                echo '<option ' . $textSelected . ' value="' . $version['id'] . '">' . $version['name'] . '</option>';
                            }
                            echo '</select>';
                        } else {
                            echo '<span>None</span>';
                        }
                        break;

                    case Field::FIELD_FIX_VERSION_CODE:
                        if ($projectVersions) {
                            $projectVersions->data_seek(0);
                            echo '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '[]" multiple="multiple" class="select2Input mousetrap" style="width: 100%;">';
                            while ($version = $projectVersions->fetch_array(MYSQLI_ASSOC)) {
                                $textSelected = '';
                                if (isset($arrayIssueVersionsTargeted) && in_array($version['id'], $arrayIssueVersionsTargeted)) {
                                    $textSelected = 'selected="selected"';
                                }
                                echo '<option ' . $textSelected . ' value="' . $version['id'] . '">' . $version['name'] . '</option>';
                            }
                            echo '</select>';
                        } else {
                            echo '<span>None</span>';
                        }
                        break;

                    case Field::FIELD_ENVIRONMENT_CODE:
                        echo '<textarea ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" rows="2" class="inputTextAreaLarge mousetrap" name="' . $field['field_code'] . '">' . $issueData['environment'] . '</textarea>';
                        break;
                    case Field::FIELD_ATTACHMENT_CODE:
                        echo '<input ' . $requiredHTML . ' id="field_type_attachment" type="file" name="' . $field['field_code'] . '[]" multiple=""/>';
                        echo '<div id="progress"></div>';
                        echo '<div id="fileList"></div>';

                        break;

                    case $fieldCodeNULL:
                        $fieldValue = UbirimiContainer::get()['repository']->get(Field::class)->getCustomFieldValueByFieldId($issueId, $field['field_id']);
                        // deal with the custom fields
                        switch ($field['type_code']) {
                            case Field::CUSTOM_FIELD_TYPE_SMALL_TEXT_CODE:
                                echo '<input ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="inputTextLarge mousetrap" type="text" value="' . htmlspecialchars($fieldValue['value'], ENT_QUOTES) . '" name="' . $field['type_code'] . '" />';
                                break;

                            case Field::CUSTOM_FIELD_TYPE_BIG_TEXT_CODE:
                                echo '<textarea ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" rows="2" class="inputTextAreaLarge mousetrap" name="' . $field['field_code'] . '">' . $fieldValue['value'] . '</textarea>';
                                break;

                            case Field::CUSTOM_FIELD_TYPE_DATE_PICKER_CODE:
                                $stringDate = '';
                                if ($fieldValue['value'])
                                    $stringDate = Util::getFormattedDate($fieldValue['value'], $clientSettings['timezone']);
                                echo '<input ' . $requiredHTML . ' class="inputText" value="' . $stringDate . '" name="' . $field['field_code'] . '" type="text" value="" id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" />';
                                break;

                            case Field::CUSTOM_FIELD_TYPE_DATE_TIME_PICKER_CODE:
                                $stringDate = '';
                                if ($fieldValue['value'])
                                    $stringDate = Util::getFormattedDate($fieldValue['value'], $clientSettings['timezone']);
                                echo '<input ' . $requiredHTML . ' class="inputText" value="' . $stringDate . '" name="' . $field['field_code'] . '" type="text" value="" id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" />';
                                break;

                            case Field::CUSTOM_FIELD_TYPE_NUMBER_CODE:
                                echo '<input ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="inputTextLarge mousetrap" type="text" value="' . $fieldValue['value'] . '" name="' . $field['type_code'] . '" />';
                                break;

                            case Field::CUSTOM_FIELD_TYPE_SELECT_LIST_SINGLE_CHOICE_CODE:

                                $possibleValues = UbirimiContainer::get()['repository']->get(Field::class)->getDataByFieldId($field['field_id']);

                                echo '<select ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" name="' . $field['type_code'] . '" class="mousetrap select2InputMedium">';
                                echo '<option value="">None</option>';
                                while ($possibleValues && $customValue = $possibleValues->fetch_array(MYSQLI_ASSOC)) {
                                    $selectedHTML = '';
                                    if ($fieldValue['value'] == $customValue['id']) {
                                        $selectedHTML = 'selected="selected"';
                                    }
                                    echo '<option ' . $selectedHTML . ' value="' . $customValue['id'] . '">' . $customValue['value'] . '</option>';
                                }
                                echo '</select>';
                                break;

                            case Field::CUSTOM_FIELD_TYPE_USER_PICKER_MULTIPLE_USER_CODE:
                                $customFieldsDataUserPickerMultipleUserData = UbirimiContainer::get()['repository']->get(CustomField::class)->getUserPickerData($issueId, $field['field_id']);

                                $customFieldsDataUserPickerMultipleUser = $customFieldsDataUserPickerMultipleUserData[$field['field_id']];

                                echo '<select ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="select2InputLarge mousetrap" type="text" multiple="multiple" name="' . $field['type_code'] . '[]">';
                                while ($allUsers && $systemUser = $allUsers->fetch_array(MYSQLI_ASSOC)) {
                                    $userFound = false;
                                    if ($customFieldsDataUserPickerMultipleUser) {
                                        foreach ($customFieldsDataUserPickerMultipleUser as $fieldUser) {
                                            if ($fieldUser['user_id'] == $systemUser['id']) {
                                                $userFound = true;
                                                break;
                                            }
                                        }
                                    }

                                    $textSelected = '';
                                    if ($userFound) {
                                        $textSelected = 'selected="selected"';
                                    }
                                    echo '<option ' . $textSelected . ' value="' . $systemUser['id'] . '">' . $systemUser['first_name'] . ' ' . $systemUser['last_name'] . '</option>';
                                }
                                echo '</select>';
                                $allUsers->data_seek(0);
                                break;
                        }
                        if ($field['description']) {
                            echo '<div class="smallDescription">' . $field['description'] . '</div>';
                        }

                        break;
                }
            echo '</td>';
        echo '</tr>';
    }
}

if ($timeTrackingFlag) {
    if (in_array($timeTrackingFieldId, $fieldsPlacedOnScreen)) {
        // deal with the time tracking fields
        for ($i = 0; $i < count($fieldData); $i++) {
            if ($fieldData[$i]['field_code'] == Field::FIELD_ISSUE_TIME_TRACKING_CODE) {

                $arrayData = Util::checkKeyAndValueInArray('field_id', $fieldData[$i]['field_id'], $fieldData);
                $mandatoryStarHTML = '';
                if ($arrayData && $arrayData['visible_flag']) {
                    if ($arrayData['required_flag'])
                        $mandatoryStarHTML = '<span class="mandatory">*</span>';

                    $requiredHTML = $arrayData['required_flag'] ? 'required="1"' : 'required="0"';
                    echo '<tr>';
                        echo '<td valign="top">Original Estimate ' . $mandatoryStarHTML . '</td>';
                        echo '<td>';
                            echo '<input class="inputText" style="width: 100px" ' . $requiredHTML . ' id="field_type_time_tracking_original_estimate" type="text" name="field_type_time_tracking_original_estimate" value="' . $issueData['original_estimate'] . '" /> ';
                            echo '<span>(eg. 3w 4d 12h)</span>';
                            echo '<div class="smallDescription">The original estimate of how much work is involved in resolving this issue.</div>';
                        echo '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td valign="top">Remaining Estimate ' . $mandatoryStarHTML . '</td>';
                        echo '<td>';
                            echo '<input class="inputText" style="width: 100px" ' . $requiredHTML . ' id="field_type_time_tracking_remaining_estimate" type="text" name="field_type_time_tracking_remaining_estimate" value="' . $issueData['remaining_estimate'] . '" /> ';
                            echo '<span>(eg. 3w 4d 12h)</span>';
                            echo '<div class="smallDescription">An estimate of how much work remains until this issue will be resolved.</div>';
                        echo '</td>';
                    echo '</tr>';
                }
            }
        }
    }
}

for ($i = 0; $i < count($fieldData); $i++) {
    if ($fieldData[$i]['field_code'] != Field::FIELD_ISSUE_TIME_TRACKING_CODE) {
        if (!in_array($fieldData[$i]['field_id'], $fieldsPlacedOnScreen) && $fieldData[$i]['required_flag']) {
            echo '<input type="hidden" description="' . Field::$fieldTranslatio[$fieldData[$i]['field_code']] . '" required="1" id="field_type_' . $fieldData[$i]['field_code'] . '" name="' . $fieldData[$i]['field_code'] . '" />';
        }
    }
}

echo '</table>';