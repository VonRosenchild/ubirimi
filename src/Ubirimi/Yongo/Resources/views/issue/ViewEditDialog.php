<?php

    use Ubirimi\Repository\Client;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueComponent;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;
    use Ubirimi\Yongo\Repository\Issue\IssueVersion;
    use Ubirimi\Yongo\Repository\Issue\SystemOperation;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\Project;

    $issueId = $issueData['id'];
    $projectId = $issueData['issue_project_id'];
    $project = Project::getById($projectId);

    $screenData = Project::getScreenData($project, $issueTypeId, SystemOperation::OPERATION_EDIT);

    $reporterUsers = Project::getUsersWithPermission($projectId, Permission::PERM_CREATE_ISSUE);
    $issuePriorities = IssueSettings::getAllIssueSettings('priority', $clientId);
    $projectIssueTypes = Project::getIssueTypes($projectId, 0);

    $assignableUsers = Project::getUsersWithPermission($projectId, Permission::PERM_ASSIGNABLE_USER);
    $userHasModifyReporterPermission = Project::userHasPermission($projectId, Permission::PERM_MODIFY_REPORTER, $loggedInUserId);
    $userHasAssignIssuePermission = Project::userHasPermission($projectId, Permission::PERM_ASSIGN_ISSUE, $loggedInUserId);
    $userHasSetSecurityLevelPermission = Project::userHasPermission($projectId, Permission::PERM_SET_SECURITY_LEVEL, $loggedInUserId);

    $timeTrackingFlag = $session->get('yongo/settings/time_tracking_flag');

    $issueSecuritySchemeId = $project['issue_security_scheme_id'];
    $issueSecuritySchemeLevels = null;
    if ($issueSecuritySchemeId)
        $issueSecuritySchemeLevels = IssueSecurityScheme::getLevelsByIssueSecuritySchemeId($issueSecuritySchemeId);

    $projectComponents = Project::getComponents($projectId);
    $issueComponents = IssueComponent::getByIssueIdAndProjectId($issueId, $projectId);
    $arrIssueComponents = array();

    if ($issueComponents)
        while ($row = $issueComponents->fetch_array(MYSQLI_ASSOC))
            $arrIssueComponents[] = $row['project_component_id'];

    $projectVersions = Project::getVersions($projectId);
    $issue_versions_affected = IssueVersion::getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
    $arr_issue_versions_affected = array();
    if ($issue_versions_affected)
        while ($row = $issue_versions_affected->fetch_array(MYSQLI_ASSOC))
            $arr_issue_versions_affected[] = $row['project_version_id'];

    $issue_versions_targeted = IssueVersion::getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_FIX_VERSION_FLAG);
    $arr_issue_versions_targeted = array();
    if ($issue_versions_targeted)
        while ($row = $issue_versions_targeted->fetch_array(MYSQLI_ASSOC))
            $arr_issue_versions_targeted[] = $row['project_version_id'];

    $fieldData = Project::getFieldInformation($project['issue_type_field_configuration_id'], $issueTypeId, 'array');
    $fieldsPlacedOnScreen = array();

    echo '<table border="0" cellpadding="2" cellspacing="0" id="tableFieldList" class="modal-table">';
        echo '<tr>';
        echo '<td width="170">Project</td>';
        echo '<td>' . $issueData['project_name'] . '</td>';
    echo '</tr>';

    $fieldCodeNULL = null;
    while ($field = $screenData->fetch_array(MYSQLI_ASSOC)) {
        if (!$userHasSetSecurityLevelPermission && $field['field_code'] == Field::FIELD_ISSUE_SECURITY_LEVEL)
            continue;

        if ($field['field_code'] == Field::FIELD_ISSUE_TIME_TRACKING) {
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
                            echo '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="type" class="inputTextCombo mousetrap">';

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

                            echo '<select ' . $textDisabled . ' ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '" class="inputTextCombo mousetrap">';
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
                        case Field::FIELD_ISSUE_SECURITY_LEVEL:
                            if ($userHasSetSecurityLevelPermission) {
                                echo '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '" class="inputTextCombo">';
                                echo '<option value="-1">None</option>';
                                while ($issueSecuritySchemeLevel = $issueSecuritySchemeLevels->fetch_array(MYSQLI_ASSOC)) {
                                    $text = '';
                                    if ($issueSecuritySchemeLevel['id'] == $issueData[Field::FIELD_ISSUE_SECURITY_LEVEL])
                                        $text = 'selected="selected"';

                                    echo '<option ' . $text . ' value="' . $issueSecuritySchemeLevel['id'] . '">' . $issueSecuritySchemeLevel['name'] . '</option>';
                                }
                                echo '</select>';
                            }
                            break;

                        case Field::FIELD_PRIORITY_CODE:
                            echo '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '" class="inputTextCombo mousetrap">';
                            while ($priority = $issuePriorities->fetch_array(MYSQLI_ASSOC)) {
                                $text = '';
                                if ($priority['id'] == $issueData[Field::FIELD_PRIORITY_CODE])
                                    $text = 'selected="selected"';
                                echo '<option ' . $text . ' value="' . $priority['id'] . '">' . $priority['name'] . '</option>';
                            }
                            echo '</select>';
                            break;

                        case Field::FIELD_ASSIGNEE_CODE:
                            $allowUnassignedIssuesFlag = Client::getYongoSetting($clientId, 'allow_unassigned_issues_flag');

                            $textDisabled = '';
                            if (!$userHasAssignIssuePermission)
                                $textDisabled = 'disabled="disabled"';

                            echo '<select ' . $textDisabled . ' ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '" class="inputTextCombo mousetrap">';
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
                                Project::renderTreeComponentsInCombobox($projectComponents, 0, $arrIssueComponents, $printedComponents);
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
                                    if (in_array($version['id'], $arr_issue_versions_affected))
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
                                    if (in_array($version['id'], $arr_issue_versions_targeted))
                                        $textSelected = 'selected="selected"';

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
                            $fieldValue = Field::getCustomFieldValueByFieldId($issueId, $field['field_id']);

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
                                        $stringDate = Util::getFormattedDate($fieldValue['value']);
                                    echo '<input ' . $requiredHTML . ' value="' . $stringDate . '" name="' . $field['field_code'] . '" type="text" value="" id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" />';
                                    break;

                                case Field::CUSTOM_FIELD_TYPE_DATE_TIME_PICKER_CODE:
                                    $stringDate = '';
                                    if ($fieldValue['value'])
                                        $stringDate = Util::getFormattedDate($fieldValue['value']);
                                    echo '<input ' . $requiredHTML . ' value="' . $stringDate . '" name="' . $field['field_code'] . '" type="text" value="" id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" />';
                                    break;

                                case Field::CUSTOM_FIELD_TYPE_NUMBER_CODE:
                                    echo '<input ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="inputTextLarge mousetrap" type="text" value="' . $fieldValue['value'] . '" name="' . $field['type_code'] . '" />';
                                    break;

                                case Field::CUSTOM_FIELD_TYPE_SELECT_LIST_SINGLE_CHOICE:

                                    $possibleValues = Field::getDataByFieldId($field['field_id']);

                                    echo '<select ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '" name="' . $field['type_code'] . '" class="mousetrap inputTextCombo">';
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
        // deal with the time tracking fields
        for ($i = 0; $i < count($fieldData); $i++) {
            if ($fieldData[$i]['field_code'] == Field::FIELD_ISSUE_TIME_TRACKING) {

                $arrayData = Util::checkKeyAndValueInArray('field_id', $fieldData[$i]['field_id'], $fieldData);
                $mandatoryStarHTML = '';
                if ($arrayData && $arrayData['visible_flag']) {
                    if ($arrayData['required_flag'])
                        $mandatoryStarHTML = '<span class="mandatory">*</span>';

                    $requiredHTML = $arrayData['required_flag'] ? 'required="1"' : 'required="0"';
                    echo '<tr>';
                        echo '<td valign="top">Original Estimate ' . $mandatoryStarHTML . '</td>';
                        echo '<td>';
                            echo '<input style="width: 100px" ' . $requiredHTML . ' id="field_type_time_tracking_original_estimate" type="text" name="field_type_time_tracking_original_estimate" value="' . $issueData['original_estimate'] . '" /> ';
                            echo '<span>(eg. 3w 4d 12h)</span>';
                            echo '<div class="smallDescription">The original estimate of how much work is involved in resolving this issue.</div>';
                        echo '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td valign="top">Remaining Estimate ' . $mandatoryStarHTML . '</td>';
                        echo '<td>';
                            echo '<input style="width: 100px" ' . $requiredHTML . ' id="field_type_time_tracking_remaining_estimate" type="text" name="field_type_time_tracking_remaining_estimate" value="' . $issueData['remaining_estimate'] . '" /> ';
                            echo '<span>(eg. 3w 4d 12h)</span>';
                            echo '<div class="smallDescription">An estimate of how much work remains until this issue will be resolved.</div>';
                        echo '</td>';
                    echo '</tr>';
                }
            }
        }
    }
    for ($i = 0; $i < count($fieldData); $i++) {
        if ($fieldData[$i]['field_code'] != Field::FIELD_ISSUE_TIME_TRACKING) {
            if (!in_array($fieldData[$i]['field_id'], $fieldsPlacedOnScreen) && $fieldData[$i]['required_flag']) {
                echo '<input type="hidden" description="' . Field::$fieldTranslatio[$fieldData[$i]['field_code']] . '" required="1" id="field_type_' . $fieldData[$i]['field_code'] . '" name="' . $fieldData[$i]['field_code'] . '" />';
            }
        }
    }

    echo '</table>';