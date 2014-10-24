<?php

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\CustomField;

while ($screenData && $field = $screenData->fetch_array(MYSQLI_ASSOC)) {
    $htmlOutput .= '<tr>';

    $arrayData = Util::checkKeyAndValueInArray('field_id', $field['field_id'], $fieldData);
    $requiredHTML = $arrayData['required_flag'] ? 'required="1"' : 'required="0"';
    $mandatoryStarHTML = '';
    if ($arrayData['required_flag']) {
        $mandatoryStarHTML = '<span class="mandatory">*</span>';
    }
    $htmlOutput .= '<td valign="top">' . $field['field_name'] . ' ' . $mandatoryStarHTML . '</td>';
    $htmlOutput .= '<td>';

    switch ($field['field_code']) {

        case Field::FIELD_REPORTER_CODE:

            $htmlOutput .= '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '" class="select2Input">';
            while ($user = $reporterUsers->fetch_array(MYSQLI_ASSOC)) {
                $htmlOutput .= '<option value="' . $user['user_id'] . '">' . $user['first_name'] . ' ' . $user['last_name'] . '</option>';
            }
            $htmlOutput .= '</select>';

            break;

        case Field::FIELD_SUMMARY_CODE:
            $htmlOutput .= '<input ' . $requiredHTML . ' id="field_type_summary" class="inputTextLarge" style="width: 100%;" type="text" value="" name="' . $field['field_code'] . '" />';
            break;

        case Field::FIELD_ASSIGNEE_CODE:
            $allowUnassignedIssuesFlag = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getYongoSetting($clientId, 'allow_unassigned_issues_flag');

            $htmlOutput .= '<select ' . $requiredHTML . ' id="field_type_assignee" name="' . $field['field_code'] . '" class="select2Input">';
            if ($allowUnassignedIssuesFlag)
                $htmlOutput .= '<option value="-1">No one</option>';

            while ($assignableUsers && $user = $assignableUsers->fetch_array(MYSQLI_ASSOC)) {
                $textSelected = '';
                if ($issue['user_assigned_id'] == $user['user_id']) {
                    $textSelected = 'selected="selected"';
                }
                $htmlOutput .= '<option ' . $textSelected . ' value="' . $user['user_id'] . '">' . $user['first_name'] . ' ' . $user['last_name'] . '</option>';
            }
            $htmlOutput .= '</select>';
            break;

        case Field::FIELD_DESCRIPTION_CODE:
            $htmlOutput .= '<textarea ' . $requiredHTML . ' id="field_type_description" class="inputTextAreaLarge" name="' . $field['field_code'] . '" style="width: 100%;"></textarea>';
            break;

        case Field::FIELD_DUE_DATE_CODE:
            $htmlOutput .= '<input ' . $requiredHTML . ' id="field_type_due_date" name="' . $field['field_code'] . '" type="text" value="" />';
            break;

        case Field::FIELD_COMPONENT_CODE:
            if ($projectComponents) {
                $htmlOutput .= '<select ' . $requiredHTML . ' id="field_type_component" name="' . $field['field_code'] . '[]" multiple="multiple" class="select2Input" style="width: 400px;">';
                while ($component = $projectComponents->fetch_array(MYSQLI_ASSOC)) {
                    $htmlOutput .= '<option value="' . $component['id'] . '">' . $component['name'] . '</option>';
                }
                $htmlOutput .= '</select>';
            } else {
                $htmlOutput .= '<span>None</span>';
            }

            break;

        case Field::FIELD_RESOLUTION_CODE:
            if ($resolutions) {
                $htmlOutput .= '<select ' . $requiredHTML . ' class="select2Input" id="field_type_resolution" name="' . $field['field_code'] . '[]">';
                while ($resolution = $resolutions->fetch_array(MYSQLI_ASSOC)) {
                    $htmlOutput .= '<option value="' . $resolution['id'] . '">' . $resolution['name'] . '</option>';
                }
                $htmlOutput .= '</select>';
            } else {
                $htmlOutput .= '<span>None</span>';
            }
            break;

        case Field::FIELD_AFFECTS_VERSION_CODE:
            if ($projectVersions) {
                $htmlOutput .= '<select ' . $requiredHTML . ' class="select2Input" id="field_type_affects_version" name="' . $field['field_code'] . '">';
                while ($version = $projectVersions->fetch_array(MYSQLI_ASSOC)) {
                    $htmlOutput .= '<option value="' . $version['id'] . '">' . $version['name'] . '</option>';
                }
                $htmlOutput .= '</select>';
            } else {
                $htmlOutput .= '<span>None</span>';
            }
            break;

        case Field::FIELD_FIX_VERSION_CODE:
            if ($projectVersions) {
                $projectVersions->data_seek(0);
                $htmlOutput .= '<select ' . $requiredHTML . ' class="select2Input" id="field_type_fix_version" name="' . $field['field_code'] . '">';
                while ($version = $projectVersions->fetch_array(MYSQLI_ASSOC)) {
                    $htmlOutput .= '<option value="' . $version['id'] . '">' . $version['name'] . '</option>';
                }
                $htmlOutput .= '</select>';
            } else {
                $htmlOutput .= '<span>None</span>';
            }
            break;

        case Field::FIELD_ENVIRONMENT_CODE:
            $htmlOutput .= '<textarea ' . $requiredHTML . ' id="field_type_environment" rows="2" class="inputTextAreaLarge" name="' . $field['field_code'] . '"></textarea>';
            break;

        case Field::FIELD_COMMENT_CODE:
            $htmlOutput .= '<textarea ' . $requiredHTML . ' id="field_type_comment" rows="2" class="inputTextAreaLarge" name="' . $field['field_code'] . '"></textarea>';
            break;

        case Field::FIELD_ATTACHMENT_CODE:
            $htmlOutput .= '<input ' . $requiredHTML . ' id="field_type_attachment" type="file" name="' . $field['field_code'] . '[]" multiple="multiple"/>';
            $htmlOutput .= '<div id="progress"></div>';
            $htmlOutput .= '<div id="fileList"></div>';

            break;

        // deal with the custom fields
        case $fieldCodeNULL:

            $fieldValue = UbirimiContainer::get()['repository']->get(Field::class)->getCustomFieldValueByFieldId($issueId, $field['field_id']);
            switch ($field['type_code']) {
                case Field::CUSTOM_FIELD_TYPE_SMALL_TEXT_CODE:
                    $htmlOutput .= '<input ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="inputTextLarge" type="text" value="' . $fieldValue['value'] . '" name="' . $field['type_code'] . '" />';
                    break;

                case Field::CUSTOM_FIELD_TYPE_BIG_TEXT_CODE:
                    $htmlOutput .= '<textarea ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" rows="2" class="inputTextAreaLarge" name="' . $field['field_code'] . '">' . $fieldValue['value'] . '</textarea>';
                    break;

                case Field::CUSTOM_FIELD_TYPE_DATE_PICKER_CODE:
                    $stringDate = '';
                    if ($fieldValue['value'])
                        $stringDate = date('Y-m-d', strtotime($fieldValue['value']));
                    $htmlOutput .= '<input ' . $requiredHTML . ' value="' . $stringDate . '" name="' . $field['field_code'] . '" type="text" class="inputText" value="" id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" />';
                    break;

                case Field::CUSTOM_FIELD_TYPE_DATE_TIME_PICKER_CODE:
                    $stringDate = '';
                    if ($fieldValue['value'])
                        $stringDate = date('Y-m-d H:i', strtotime($fieldValue['value']));
                    $htmlOutput .= '<input ' . $requiredHTML . ' value="' . $stringDate . '" name="' . $field['field_code'] . '" type="text" class="inputText" value="" id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" />';
                    break;

                case Field::CUSTOM_FIELD_TYPE_NUMBER_CODE:
                    $htmlOutput .= '<input ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="inputTextLarge" type="text" value="' . $fieldValue['value'] . '" name="' . $field['type_code'] . '" />';
                    break;

                case Field::CUSTOM_FIELD_TYPE_SELECT_LIST_SINGLE_CHOICE_CODE:
                    $possibleValues = UbirimiContainer::get()['repository']->get(Field::class)->getDataByFieldId($field['field_id']);
                    $htmlOutput .= '<select ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '" name="' . $field['type_code'] . '" class="mousetrap select2InputMedium">';
                    $htmlOutput .= '<option value="">None</option>';
                    while ($possibleValues && $customValue = $possibleValues->fetch_array(MYSQLI_ASSOC)) {
                        $htmlOutput .= '<option value="' . $customValue['id'] . '">' . $customValue['value'] . '</option>';
                    }
                    $htmlOutput .= '</select>';
                    break;

                case Field::CUSTOM_FIELD_TYPE_USER_PICKER_MULTIPLE_USER_CODE:
                    $customFieldsDataUserPickerMultipleUserData = UbirimiContainer::get()['repository']->get(CustomField::class)->getUserPickerData($issueId, $field['field_id']);
                    $customFieldsDataUserPickerMultipleUser = $customFieldsDataUserPickerMultipleUserData[$field['field_id']];

                    $htmlOutput .= '<select ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="select2Input mousetrap" type="text" multiple="multiple" name="' . $field['type_code'] . '[]">';
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
                        $htmlOutput .= '<option ' . $textSelected . ' value="' . $systemUser['id'] . '">' . $systemUser['first_name'] . ' ' . $systemUser['last_name'] . '</option>';
                    }
                    $htmlOutput .= '</select>';
                    $allUsers->data_seek(0);
                    break;
            }

            if ($field['description'])
                $htmlOutput .= '<div class="smallDescription">' . $field['description'] . '</div>';

            break;
    }

    $htmlOutput .= '</td>';
    $htmlOutput .= '</tr>';
}

$htmlOutput .= '</table>';

echo $htmlOutput;