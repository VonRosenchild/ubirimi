<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Util;
use Ubirimi\Yongo\Helper\IssueHelper;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

$screenData = UbirimiContainer::get()['repository']->get(YongoProject::class)->getScreenData($projectData, $issueTypeId, $sysOperationId);

    $projectId = $projectData['id'];
    $projectComponents = UbirimiContainer::get()['repository']->get(YongoProject::class)->getComponents($projectId);

    $issueSecuritySchemeId = $projectData['issue_security_scheme_id'];
    $issueSecuritySchemeLevels = null;

    if ($issueSecuritySchemeId) {
        $issueSecuritySchemeLevels = UbirimiContainer::get()['repository']->get(IssueSecurityScheme::class)->getLevelsByIssueSecuritySchemeId($issueSecuritySchemeId);
    }

    $projectVersions = UbirimiContainer::get()['repository']->get(YongoProject::class)->getVersions($projectId);
    $issuePriorities = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('priority', $clientId);
    $issueResolutions = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('resolution', $clientId);
    $assignableUsers = UbirimiContainer::get()['repository']->get(YongoProject::class)->getUsersWithPermission($projectId, Permission::PERM_ASSIGNABLE_USER);
    $reporterUsers = UbirimiContainer::get()['repository']->get(YongoProject::class)->getUsersWithPermission($projectId, Permission::PERM_CREATE_ISSUE);
    $allUsers = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getByClientId($session->get('client/id'));

    $userHasModifyReporterPermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectId, Permission::PERM_MODIFY_REPORTER, $loggedInUserId);
    $userHasAssignIssuePermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectId, Permission::PERM_ASSIGN_ISSUE, $loggedInUserId);
    $userHasSetSecurityLevelPermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectId, Permission::PERM_SET_SECURITY_LEVEL, $loggedInUserId);

    $timeTrackingFlag = $session->get('yongo/settings/time_tracking_flag');
    $timeTrackingFieldId = null;
    $fieldData = UbirimiContainer::get()['repository']->get(YongoProject::class)->getFieldInformation($projectData['issue_type_field_configuration_id'], $issueTypeId, 'array');

    $fieldCodeNULL = null;
    $fieldsPlacedOnScreen = array();

    while ($field = $screenData->fetch_array(MYSQLI_ASSOC)) {

        if ($field['field_code'] == Field::FIELD_ISSUE_TYPE_CODE) {
            $fieldsPlacedOnScreen[] = $field['field_id'];
            continue;
        }

        if ($field['field_code'] == Field::FIELD_ISSUE_TIME_TRACKING_CODE) {
            $fieldsPlacedOnScreen[] = $field['field_id'];
            $timeTrackingFieldId = $field['field_id'];
            continue;
        }

        if (!$userHasSetSecurityLevelPermission && $field['field_code'] == Field::FIELD_ISSUE_SECURITY_LEVEL_CODE) {
            continue;
        }

        $arrayData = Util::checkKeyAndValueInArray('field_id', $field['field_id'], $fieldData);

        if ($arrayData && $arrayData['visible_flag']) {
            $fieldsPlacedOnScreen[] = $field['field_id'];
            $requiredHTML = $arrayData['required_flag'] ? 'required="1"' : 'required="0"';
            $mandatoryStarHTML = '';
            if ($arrayData['required_flag'])
                $mandatoryStarHTML = '<span class="mandatory">*</span>';

            echo '<tr>';
                echo '<td width="160px" valign="top">' . $field['field_name'] . ' ' . $mandatoryStarHTML . '</td>';
                echo '<td>';
                    switch ($field['field_code']) {
                        case Field::FIELD_REPORTER_CODE:
                            echo IssueHelper::renderUserSelect(
                                Field::FIELD_REPORTER_CODE,
                                $reporterUsers->fetch_all(MYSQLI_ASSOC),
                                $loggedInUserId,
                                null === $userHasModifyReporterPermission
                            );
                            break;

                        case Field::FIELD_ASSIGNEE_CODE:
                            echo IssueHelper::renderUserSelect(
                                Field::FIELD_ASSIGNEE_CODE,
                                $assignableUsers->fetch_all(MYSQLI_ASSOC),
                                $projectData['lead_id'],
                                null === $userHasAssignIssuePermission,
                                $arrayData['required_flag'],
                                1 === UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getYongoSetting($clientId, 'allow_unassigned_issues_flag')
                            );
                            break;

                        case Field::FIELD_SUMMARY_CODE:
                            echo IssueHelper::renderInput(Field::FIELD_SUMMARY_CODE, $arrayData['required_flag']);
                            break;

                        case Field::FIELD_ISSUE_SECURITY_LEVEL_CODE:
                            if ($userHasSetSecurityLevelPermission) {

                                echo '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '" class="inputTextCombo mousetrap">';
                                echo '<option value="-1">None</option>';
                                if ($issueSecuritySchemeLevels) {
                                    while ($issueSecuritySchemeLevel = $issueSecuritySchemeLevels->fetch_array(MYSQLI_ASSOC)) {
                                        echo '<option value="' . $issueSecuritySchemeLevel['id'] . '">' . $issueSecuritySchemeLevel['name'] . '</option>';
                                    }
                                }
                                echo '</select>';
                            }
                            break;

                        case Field::FIELD_PRIORITY_CODE:
                            echo IssueHelper::renderSelect(
                                Field::FIELD_PRIORITY_CODE,
                                $issuePriorities->fetch_all(MYSQLI_ASSOC),
                                $arrayData['required_flag']
                            );
                            break;

                        case Field::FIELD_RESOLUTION_CODE:
                            echo IssueHelper::renderSelect(
                                Field::FIELD_RESOLUTION_CODE,
                                $issueResolutions->fetch_all(MYSQLI_ASSOC),
                                $arrayData['required_flag']
                            );
                            break;

                        case Field::FIELD_DESCRIPTION_CODE:
                            echo IssueHelper::renderTextarea(Field::FIELD_DESCRIPTION_CODE, 1, $arrayData['required_flag']);
                            break;

                        case Field::FIELD_DUE_DATE_CODE:
                            echo IssueHelper::renderInput(Field::FIELD_DUE_DATE_CODE, $arrayData['required_flag'], 'width: 110px');
                            break;

                        case Field::FIELD_COMPONENT_CODE:
                            if ($projectComponents) {
                                echo '<select size="3" ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '[]" multiple="multiple" class="select2Input mousetrap" style="width: 650px;">';
                                $printedComponents = array();
                                UbirimiContainer::get()['repository']->get(YongoProject::class)->renderTreeComponentsInCombobox($projectComponents, 0, null, $printedComponents);
                                echo '</select>';
                            } else {
                                echo '<span ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '">None</span>';
                            }

                            break;

                        case Field::FIELD_AFFECTS_VERSION_CODE:
                            if ($projectVersions) {
                                $projectVersions->data_seek(0);
                                echo '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '[]" multiple="multiple" class="select2Input mousetrap" style="width: 650px">';
                                while ($version = $projectVersions->fetch_array(MYSQLI_ASSOC)) {
                                    echo '<option value="' . $version['id'] . '">' . $version['name'] . '</option>';
                                }
                                echo '</select>';
                            } else {
                                echo '<span ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '">None</span>';
                            }
                            break;

                        case Field::FIELD_FIX_VERSION_CODE:
                            if ($projectVersions) {
                                $projectVersions->data_seek(0);
                                echo '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '[]" multiple="multiple" class="select2Input mousetrap" style="width: 650px;">';
                                while ($version = $projectVersions->fetch_array(MYSQLI_ASSOC)) {
                                    echo '<option value="' . $version['id'] . '">' . $version['name'] . '</option>';
                                }
                                echo '</select>';
                            } else {
                                echo '<span ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '">None</span>';
                            }
                            break;

                        case Field::FIELD_ENVIRONMENT_CODE:
                            echo '<textarea ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" rows="2" class="inputTextAreaLarge mousetrap" name="' . $field['field_code'] . '"></textarea>';
                            break;

                        case Field::FIELD_COMMENT_CODE:
                            echo IssueHelper::renderTextarea(Field::FIELD_COMMENT_CODE, 2, $arrayData['required_flag']);
                            break;

                        case Field::FIELD_ATTACHMENT_CODE:
                            echo '<input ' . $requiredHTML . ' id="field_type_attachment" type="file" name="' . $field['field_code'] . '[]" multiple="multiple" />';
                            echo '<div id="progress"></div>';
                            echo '<div id="fileList"></div>';
                            break;

                        case $fieldCodeNULL:

                            // deal with the custom fields
                            switch ($field['type_code']) {
                                case Field::CUSTOM_FIELD_TYPE_SMALL_TEXT_CODE:
                                    echo '<input ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="inputTextLarge mousetrap" type="text" value="" name="' . $field['type_code'] . '" />';
                                    break;

                                case Field::CUSTOM_FIELD_TYPE_BIG_TEXT_CODE:
                                    echo '<textarea ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" rows="2" class="inputTextAreaLarge mousetrap" name="' . $field['field_code'] . '"></textarea>';
                                    break;

                                case Field::CUSTOM_FIELD_TYPE_DATE_PICKER_CODE:
                                    echo '<input style="width: 110px" ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="mousetrap inputText" name="' . $field['field_code'] . '" type="text" value="" />';
                                    break;

                                case Field::CUSTOM_FIELD_TYPE_DATE_TIME_PICKER_CODE:
                                    echo '<input style="width: 140px" ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="mousetrap inputText" name="' . $field['field_code'] . '" type="text" value="" />';
                                    break;

                                case Field::CUSTOM_FIELD_TYPE_NUMBER_CODE:
                                    echo '<input ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="inputText mousetrap" name="' . $field['field_code'] . '" type="text" value="" />';
                                    break;
                                case Field::CUSTOM_FIELD_TYPE_SELECT_LIST_SINGLE_CHOICE_CODE:
                                    $possibleValues = UbirimiContainer::get()['repository']->get(Field::class)->getDataByFieldId($field['field_id']);
                                    echo '<select ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '" name="' . $field['type_code'] . '" class="mousetrap select2InputMedium">';
                                    echo '<option value="">None</option>';
                                    while ($possibleValues && $customValue = $possibleValues->fetch_array(MYSQLI_ASSOC)) {
                                        echo '<option value="' . $customValue['id'] . '">' . $customValue['value'] . '</option>';
                                    }
                                    echo '</select>';
                                    break;

                                case Field::CUSTOM_FIELD_TYPE_USER_PICKER_MULTIPLE_USER_CODE:
                                    echo '<select ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="select2Input mousetrap" name="' . $field['field_code'] . '[]" multiple="multiple" style="width: 650px;" type="text" value="">';
                                    while ($user = $allUsers->fetch_array(MYSQLI_ASSOC)) {
                                        echo '<option  value="' . $user['id'] . '">' . $user['first_name'] . ' ' . $user['last_name'] . '</option>';
                                    }
                                    echo '</select>';
                                    $allUsers->data_seek(0);
                                    break;
                            }
                            if ($field['description'])
                                echo '<div class="smallDescription">' . $field['description'] . '</div>';

                            break;
                    }
                echo '</td>';
            echo '</tr>';
        }
    }

    // deal with the time tracking fields
    if ($timeTrackingFlag) {

        if (in_array($timeTrackingFieldId, $fieldsPlacedOnScreen)) {

            $arrayData = Util::checkKeyAndValueInArray('field_id', $timeTrackingFieldId, $fieldData);

            if ($arrayData && $arrayData['visible_flag']) {
                $requiredHTML = $arrayData['required_flag'] ? 'required="1"' : 'required="0"';
                echo '<tr>';
                    echo '<td valign="top">Original Estimate</td>';
                    echo '<td>';
                        echo '<input class="inputText" style="width: 100px" ' . $requiredHTML . ' id="field_type_time_tracking_original_estimate" type="text" name="field_type_time_tracking_original_estimate" value="" /> ';
                        echo '<span>(eg. 3w 4d 12h)</span>';
                        echo '<div class="smallDescription">The original estimate of how much work is involved in resolving this issue.</div>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td valign="top">Remaining Estimate</td>';
                    echo '<td>';
                        echo '<input class="inputText" style="width: 100px" ' . $requiredHTML . ' id="field_type_time_tracking_remaining_estimate" type="text" name="field_type_time_tracking_remaining_estimate" value="" /> ';
                        echo '<span>(eg. 3w 4d 12h)</span>';
                        echo '<div class="smallDescription">An estimate of how much work remains until this issue will be resolved.</div>';
                    echo '</td>';
                echo '</tr>';
            }
        }
    }

    for ($i = 0; $i < count($fieldData); $i++) {

        if (!in_array($fieldData[$i]['field_id'], $fieldsPlacedOnScreen) && $fieldData[$i]['required_flag']) {
            if ($fieldData[$i]['field_code'] == Field::FIELD_ISSUE_SECURITY_LEVEL_CODE) {
                /* if it is not placed on screen check if there is a default level set.
                 * if there is a default level set do nothing. if not add it to the warnings
                 */

                $defaultLevel = UbirimiContainer::get()['repository']->get(IssueSecurityScheme::class)->getDefaultLevel($projectData['issue_security_scheme_id']);
                echo '<tr>';
                    echo '<td>';
                    if (!$defaultLevel) {
                        echo '<input type="hidden" description="' . Field::$fieldTranslation[$fieldData[$i]['field_code']] . '" required="1" id="field_type_' . $fieldData[$i]['field_code'] . '" name="' . $fieldData[$i]['field_code'] . '" />';
                    } else {
                        echo '<input type="hidden" description="' . Field::$fieldTranslation[$fieldData[$i]['field_code']] . '" required="1" value="' . $defaultLevel['id'] . '" id="field_type_' . $fieldData[$i]['field_code'] . '" name="' . $fieldData[$i]['field_code'] . '" />';
                    }
                    echo '</td>';
                echo '</tr>';
            } elseif ($fieldData[$i]['field_code'] != Field::FIELD_ISSUE_TIME_TRACKING_CODE) {
                echo '<tr>';
                    echo '<td>';
                        echo '<input type="hidden" description="' . Field::$fieldTranslation[$fieldData[$i]['field_code']] . '" required="1" id="field_type_' . $fieldData[$i]['field_code'] . '" name="' . $fieldData[$i]['field_code'] . '" />';
                    echo '</td>';
                echo '</tr>';
            }
        }
    }