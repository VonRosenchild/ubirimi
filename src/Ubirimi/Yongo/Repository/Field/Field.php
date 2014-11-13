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
    const FIELD_ISSUE_SECURITY_LEVEL_CODE = 'security_level';
    const FIELD_ISSUE_TIME_TRACKING_CODE = 'time_tracking';
    const FIELD_PROJECT_CODE = 'project_name';

    const CUSTOM_FIELD_TYPE_SMALL_TEXT_CODE = 'small_text_field';
    const CUSTOM_FIELD_TYPE_BIG_TEXT_CODE = 'big_text_field';
    const CUSTOM_FIELD_TYPE_DATE_PICKER_CODE = 'date_picker';
    const CUSTOM_FIELD_TYPE_DATE_TIME_PICKER_CODE = 'date_time';
    const CUSTOM_FIELD_TYPE_NUMBER_CODE = 'number';
    const CUSTOM_FIELD_TYPE_SELECT_LIST_SINGLE_CHOICE_CODE = 'select_list_single';
    const CUSTOM_FIELD_TYPE_USER_PICKER_MULTIPLE_USER_CODE = 'user_picker_multiple_user';

    const CUSTOM_FIELD_TYPE_SMALL_TEXT_CODE_ID = 1;
    const CUSTOM_FIELD_TYPE_DATE_PICKER_CODE_ID = 2;
    const CUSTOM_FIELD_TYPE_DATE_TIME_PICKER_CODE_ID = 3;
    const CUSTOM_FIELD_TYPE_BIG_TEXT_CODE_ID = 4;
    const CUSTOM_FIELD_TYPE_NUMBER_CODE_ID = 5;
    const CUSTOM_FIELD_TYPE_SELECT_LIST_SINGLE_CODE_ID = 6;
    const CUSTOM_FIELD_TYPE_USER_PICKER_MULTIPLE_USER_CODE_ID = 7;

    public static $fieldTranslation = array(
        Field::FIELD_SUMMARY_CODE => 'Summary',
        Field::FIELD_ASSIGNEE_CODE => 'Assigned to',
        Field::FIELD_DESCRIPTION_CODE => 'Description',
        Field::FIELD_REPORTER_CODE => 'Reported by',
        Field::FIELD_ISSUE_TYPE_CODE => 'Type',
        Field::FIELD_PRIORITY_CODE => 'Priority',
        Field::FIELD_STATUS_CODE => 'Status',
        Field::FIELD_ENVIRONMENT_CODE => 'Environment',
        Field::FIELD_COMPONENT_CODE => 'Components',
        Field::FIELD_FIX_VERSION_CODE => 'Target releases',
        Field::FIELD_AFFECTS_VERSION_CODE => 'Affected versions',
        Field::FIELD_RESOLUTION_CODE => 'Resolution',
        Field::FIELD_DUE_DATE_CODE => 'Due Date',
        Field::FIELD_ISSUE_SECURITY_LEVEL_CODE => 'Security Level',
        'time_spent' => 'Time Spent',
        'remaining_estimate' => 'Remaining Estimate',
        'worklog_time_spent' => 'Worklog Time Spent',
        Field::FIELD_ATTACHMENT_CODE => 'Attachment',
        Field::FIELD_PROJECT_CODE => 'Project'
    );

    public function getByClient($clientId) {
        $query = "SELECT * FROM field where client_id = ? order by name";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByClientIdAndFieldTypeId($clientId, $typeId) {
        $query = "SELECT * FROM field
                    where client_id = ?
                      and sys_field_type_id = ?
                    group by name
                    order by name";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $typeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getById($fieldId) {
        $query = "SELECT * FROM field where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $fieldId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getByCode($clientId, $code) {
        $query = "SELECT * FROM field where client_id = ? and code = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $clientId, $code);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getIssueTypesFor($fieldId) {
        $query = "SELECT issue_type.name, issue_type.id " .
            "FROM field_issue_type_data " .
            "left join issue_type on issue_type.id = field_issue_type_data.issue_type_id " .
            "where field_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $fieldId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getProjectsFor($fieldId) {
        $query = "SELECT project.name, project.id " .
            "FROM field_project_data " .
            "left join project on project.id = field_project_data.project_id " .
            "where field_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $fieldId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getCustomFieldValueByFieldId($issueId, $fieldId) {
        $query = "SELECT * " .
            "FROM issue_custom_field_data " .
            "where issue_id = ? and field_id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueId, $fieldId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array();
        else
            return null;
    }

    public function deleteByClientId($clientId) {
        $fields = UbirimiContainer::get()['repository']->get(Field::class)->getByClient($clientId);
        if ($fields) {
            while ($field = $fields->fetch_array(MYSQLI_ASSOC)) {
                $fieldId = $field['id'];

                $query = "delete from field_issue_type_data where field_id = ?";
                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("i", $fieldId);
                $stmt->execute();

                $query = "delete from field_project_data where field_id = ?";
                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("i", $fieldId);
                $stmt->execute();
            }
        }

        $query = "delete from field where client_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
    }

    public function add($clientId, $code, $name, $description, $systemFlag, $allIssueTypeFlag, $allProjectFlag) {
        $query = "INSERT INTO field(client_id, code, name, description, system_flag, all_issue_type_flag, all_project_flag) VALUES " .
            "(?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("isssiii", $clientId, $code, $name, $description, $systemFlag, $allIssueTypeFlag, $allProjectFlag);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function addData($fieldId, $value, $currentDate) {
        $query = "INSERT INTO field_data(field_id, `value`, date_created) VALUES " .
                 "(?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iss", $fieldId, $value, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getDataByFieldId($fieldId) {
        $query = "SELECT * " .
            "FROM field_data " .
            "where field_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $fieldId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getDataByFieldIdAndValue($fieldId, $value, $dataId = null) {
        $query = 'select * from `field_data` where field_id = ? and value = ?';

        if ($dataId) {
            $query .= ' and id != ' . $dataId;
        }

        $query .= ' limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $fieldId, $value);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function getDataById($id) {
        $query = 'select * from `field_data` where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function deleteDataById($customFieldDataId) {
        $field = UbirimiContainer::get()['repository']->get(Field::class)->getDataById($customFieldDataId);

        $query = "delete from field_data where id = ? limit 1";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $customFieldDataId);
        $stmt->execute();

        $query = "delete from issue_custom_field_data where field_id = ? and value = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $field['id'], $customFieldDataId);
        $stmt->execute();
    }

    public function updateDataById($id, $value, $date) {
        $query = "update field_data set `value` = ?, date_updated = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssi", $value, $date, $id);
        $stmt->execute();
    }
}
