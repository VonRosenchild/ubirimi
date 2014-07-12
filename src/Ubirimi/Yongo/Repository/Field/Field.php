<?php

namespace Ubirimi\Yongo\Repository\Field;

use Ubirimi\Container\UbirimiContainer;

class Field {

    const FIELD_SUMMARY_CODE = 'summary';
    const FIELD_PRIORITY_CODE = 'priority';
    const FIELD_ASSIGNEE_CODE = 'assignee';
    const FIELD_DESCRIPTION_CODE = 'description';
    const FIELD_DUE_DATE_CODE = 'due_date';
    const FIELD_RESOLVED_DATE_CODE = 'date_resolved';
    const FIELD_COMPONENT_CODE = 'component';
    const FIELD_AFFECTS_VERSION_CODE = 'affects_version';
    const FIELD_FIX_VERSION_CODE = 'fix_version';
    const FIELD_ENVIRONMENT_CODE = 'environment';
    const FIELD_ATTACHMENT_CODE = 'attachment';
    const FIELD_ISSUE_TYPE_CODE = 'type';
    const FIELD_REPORTER_CODE = 'reporter';
    const FIELD_RESOLUTION_CODE = 'resolution';
    const FIELD_COMMENT_CODE = 'comment';
    const FIELD_STATUS_CODE = 'status';
    const FIELD_ISSUE_SECURITY_LEVEL = 'security_level';
    const FIELD_ISSUE_TIME_TRACKING = 'time_tracking';

    const CUSTOM_FIELD_TYPE_SMALL_TEXT_CODE = 'small_text_field';
    const CUSTOM_FIELD_TYPE_BIG_TEXT_CODE = 'big_text_field';
    const CUSTOM_FIELD_TYPE_DATE_PICKER_CODE = 'date_picker';
    const CUSTOM_FIELD_TYPE_DATE_TIME_PICKER_CODE = 'date_time';
    const CUSTOM_FIELD_TYPE_NUMBER_CODE = 'number';
    const CUSTOM_FIELD_SELECT_LIST_SINGLE_CHOICE = 'select_list_single';

    const CUSTOM_FIELD_TYPE_SMALL_TEXT_CODE_ID = 1;
    const CUSTOM_FIELD_TYPE_DATE_PICKER_CODE_ID = 2;
    const CUSTOM_FIELD_TYPE_DATE_TIME_PICKER_CODE_ID = 3;
    const CUSTOM_FIELD_TYPE_BIG_TEXT_CODE_ID = 4;
    const CUSTOM_FIELD_TYPE_NUMBER_CODE_ID = 5;
    const CUSTOM_FIELD_SELECT_LIST_SINGLE_CHOICE_ID = 6;

    public static $fieldTranslation = array(Field::FIELD_SUMMARY_CODE => 'Summary', Field::FIELD_ASSIGNEE_CODE => 'Assigned to', Field::FIELD_DESCRIPTION_CODE => 'Description',
                                    Field::FIELD_REPORTER_CODE => 'Reported by', Field::FIELD_ISSUE_TYPE_CODE => 'Type', Field::FIELD_PRIORITY_CODE => 'Priority',
                                    Field::FIELD_STATUS_CODE => 'Status', Field::FIELD_ENVIRONMENT_CODE => 'Environment', Field::FIELD_COMPONENT_CODE => 'Components',
                                    Field::FIELD_FIX_VERSION_CODE => 'Target releases', Field::FIELD_AFFECTS_VERSION_CODE => 'Affected versions', Field::FIELD_RESOLUTION_CODE => 'Resolution',
                                    Field::FIELD_DUE_DATE_CODE => 'Due Date', Field::FIELD_ISSUE_SECURITY_LEVEL => 'Security Level', 'time_spent' => 'Time Spent',
                                    'remaining_estimate' => 'Remaining Estimate', 'worklog_time_spent' => 'Worklog Time Spent', Field::FIELD_ATTACHMENT_CODE => 'Attachment');

    public static function getByClient($clientId) {
        $query = "SELECT * FROM field where client_id = ? order by name";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $clientId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getById($fieldId) {
        $query = "SELECT * FROM field where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $fieldId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function getByCode($clientId, $code) {
        $query = "SELECT * FROM field where client_id = ? and code = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("is", $clientId, $code);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function getIssueTypesFor($fieldId) {
        $query = "SELECT issue_type.name, issue_type.id " .
            "FROM field_issue_type_data " .
            "left join issue_type on issue_type.id = field_issue_type_data.issue_type_id " .
            "where field_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $fieldId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getProjectsFor($fieldId) {
        $query = "SELECT project.name, project.id " .
            "FROM field_project_data " .
            "left join project on project.id = field_project_data.project_id " .
            "where field_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $fieldId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getCustomFieldValueByFieldId($issueId, $fieldId) {
        $query = "SELECT * " .
            "FROM issue_custom_field_data " .
            "where issue_id = ? and field_id = ? " .
            "limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $issueId, $fieldId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array();
            else
                return null;
        }
    }

    public static function deleteByClientId($clientId) {
        $fields = Field::getByClient($clientId);
        if ($fields) {
            while ($field = $fields->fetch_array(MYSQLI_ASSOC)) {
                $fieldId = $field['id'];

                $query = "delete from field_issue_type_data where field_id = ?";
                if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                    $stmt->bind_param("i", $fieldId);
                    $stmt->execute();
                }

                $query = "delete from field_project_data where field_id = ?";
                if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                    $stmt->bind_param("i", $fieldId);
                    $stmt->execute();
                }
            }
        }

        $query = "delete from field where client_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $clientId);
            $stmt->execute();
        }
    }

    public static function add($clientId, $code, $name, $description, $systemFlag, $allIssueTypeFlag, $allProjectFlag) {
        $query = "INSERT INTO field(client_id, code, name, description, system_flag, all_issue_type_flag, all_project_flag) VALUES " .
            "(?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("isssiii", $clientId, $code, $name, $description, $systemFlag, $allIssueTypeFlag, $allProjectFlag);
            $stmt->execute();
            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function getDataByFieldId($fieldId) {
        $query = "SELECT * " .
            "FROM field_data " .
            "where field_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $fieldId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }
}