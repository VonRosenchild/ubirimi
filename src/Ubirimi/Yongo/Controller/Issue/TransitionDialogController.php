<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\Project;
    use Ubirimi\Yongo\Repository\Screen\Screen;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $workflowId = $_GET['id'];
    $stepIdFrom = $_GET['step_id_from'];
    $stepIdTo = $_GET['step_id_to'];

    $projectId = $_GET['project_id'];
    $issueId = $_GET['issue_id'];
    $assignableUsers = Project::getUsersWithPermission($projectId, Permission::PERM_ASSIGNABLE_USER);
    $projectData = Project::getById($projectId);
    $issue = Issue::getByIdSimple($issueId);
    $workflowData = Workflow::getDataByStepIdFromAndStepIdTo($workflowId, $stepIdFrom, $stepIdTo);
    $screenId = $workflowData['screen_id'];

    $screenData = Screen::getDataById($screenId);
    $screenMetadata = Screen::getMetaDataById($screenId);
    $resolutions = IssueSettings::getAllIssueSettings('resolution', $clientId);
    $projectComponents = Project::getComponents($projectId);
    $projectVersions = Project::getVersions($projectId);
    $htmlOutput = '';
    $htmlOutput .= '<table class="modal-table">';

    $reporterUsers = Project::getUsersWithPermission($projectId, Permission::PERM_CREATE_ISSUE);
    $fieldCodeNULL = null;

    $fieldData = Project::getFieldInformation($projectData['issue_type_field_configuration_id'], $issue['type_id'], 'array');

    while ($screenData && $field = $screenData->fetch_array(MYSQLI_ASSOC)) {
        $htmlOutput .= '<tr>';

            $arrayData = Util::checkKeyAndValueInArray('field_id', $field['field_id'], $fieldData);
            $requiredHTML = $arrayData['required_flag'] ? 'required="1"' : 'required="0"';
            $mandatoryStarHTML = '';
            if ($arrayData['required_flag'])
                $mandatoryStarHTML = '<span class="mandatory">*</span>';

            $htmlOutput .= '<td valign="top">' . $field['field_name'] . ' ' . $mandatoryStarHTML . '</td>';
            $htmlOutput .= '<td>';

            switch ($field['field_code']) {

                case Field::FIELD_REPORTER_CODE:

                    $htmlOutput .= '<select ' . $requiredHTML . ' id="field_type_' . $field['field_code'] . '" name="' . $field['field_code'] . '" class="inputTextCombo">';
                    while ($user = $reporterUsers->fetch_array(MYSQLI_ASSOC)) {
                        $htmlOutput .= '<option value="' . $user['user_id'] . '">' . $user['first_name'] . ' ' . $user['last_name'] . '</option>';
                    }
                    $htmlOutput .= '</select>';

                    break;

                case Field::FIELD_SUMMARY_CODE:
                    $htmlOutput .= '<input ' . $requiredHTML . ' id="field_type_summary" class="inputTextLarge" style="width: 100%;" type="text" value="" name="' . $field['field_code'] . '" />';
                    break;

//                case Field::FIELD_PRIORITY_CODE:
//                    $htmlOutput .= '<select ' . $requiredHTML . ' id="field_type_priority" name="' . $field['field_code'] . '" class="inputTextCombo">';
//                    while ($priority = $issuePriorities->fetch_array(MYSQLI_ASSOC)) {
//                        $htmlOutput .= '<option value="' . $priority['id'] . '">' . $priority['name'] . '</option>';
//                    }
//                    $htmlOutput .= '</select>';
//                    break;

                case Field::FIELD_ASSIGNEE_CODE:
                    $allowUnassignedIssuesFlag = Client::getYongoSetting($clientId, 'allow_unassigned_issues_flag');

                    $htmlOutput .= '<select ' . $requiredHTML . ' id="field_type_assignee" name="' . $field['field_code'] . '" class="inputTextCombo">';
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
                        $htmlOutput .= '<select ' . $requiredHTML . ' id="field_type_component" name="' . $field['field_code'] . '[]" multiple="multiple" class="chzn-select" style="width: 400px;">';
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
                        $htmlOutput .= '<select ' . $requiredHTML . ' class="inputTextCombo" id="field_type_resolution" name="' . $field['field_code'] . '[]">';
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
                        $htmlOutput .= '<select ' . $requiredHTML . ' class="inputTextCombo" id="field_type_affects_version" name="' . $field['field_code'] . '">';
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
                        $htmlOutput .= '<select ' . $requiredHTML . ' class="inputTextCombo" id="field_type_fix_version" name="' . $field['field_code'] . '">';
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

                    $fieldValue = Field::getCustomFieldValueByFieldId($issueId, $field['field_id']);
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
                            $htmlOutput .= '<input ' . $requiredHTML . ' value="' . $stringDate . '" name="' . $field['field_code'] . '" type="text" value="" id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" />';
                            break;

                        case Field::CUSTOM_FIELD_TYPE_DATE_TIME_PICKER_CODE:
                            $stringDate = '';
                            if ($fieldValue['value'])
                                $stringDate = date('Y-m-d H:i', strtotime($fieldValue['value']));
                            $htmlOutput .= '<input ' . $requiredHTML . ' value="' . $stringDate . '" name="' . $field['field_code'] . '" type="text" value="" id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" />';
                            break;

                        case Field::CUSTOM_FIELD_TYPE_NUMBER_CODE:
                            $htmlOutput .= '<input ' . $requiredHTML . ' id="field_custom_type_' . $field['field_id'] . '_' . $field['type_code'] . '" class="inputTextLarge" type="text" value="' . $fieldValue['value'] . '" name="' . $field['type_code'] . '" />';
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