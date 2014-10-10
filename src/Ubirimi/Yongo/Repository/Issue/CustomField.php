<?php

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;

class CustomField
{
    public static function saveCustomFieldsData($issueId, $issueCustomFieldsData, $currentDate) {
        foreach ($issueCustomFieldsData as $key => $value) {
            $keyData = explode("_", $key);
            $fieldId = $keyData[0];

            if (is_array($value)) {
                for ($i = 0; $i < count($value); $i++) {
                    $query = "INSERT INTO issue_custom_field_data(issue_id, field_id, `value`, date_created) VALUES (?, ?, ?, ?)";
                    $valueField = $value[$i];

                    $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                    $stmt->bind_param("iiss", $issueId, $fieldId, $valueField, $currentDate);
                    $stmt->execute();
                }
            } else {
                if ($value != '' && $value != null) {
                    $query = "INSERT INTO issue_custom_field_data(issue_id, field_id, `value`, date_created) VALUES (?, ?, ?, ?)";

                    $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                    $stmt->bind_param("iiss", $issueId, $fieldId, $value, $currentDate);
                    $stmt->execute();
                }
            }
        }
    }

    public static function getCustomFieldsData($issueId) {
        $query = 'SELECT coalesce(field_data.value, issue_custom_field_data.value) as value, field.name, sys_field_type.code ' .
            'FROM issue_custom_field_data ' .
            'LEFT JOIN field on field.id = issue_custom_field_data.field_id ' .
            'left join field_data on field_data.id = issue_custom_field_data. value ' .
            'left join sys_field_type on sys_field_type.id = field.sys_field_type_id ' .
            'WHERE issue_id = ? and ' .
            'sys_field_type.id IN (1, 2, 3, 4, 5, 6)';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function updateCustomFieldsData($issueId, $issueCustomFieldsData, $currentDate) {
        foreach ($issueCustomFieldsData as $key => $value) {
            $keyData = explode("_", $key);
            $fieldId = $keyData[0];
            if (!empty($value)) {
                if (is_array($value)) {
                    $query = "delete from issue_custom_field_data where issue_id = ? and field_id = ?";

                    $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                    $stmt->bind_param("ii", $issueId, $fieldId);
                    $stmt->execute();

                    for ($i = 0; $i < count($value); $i++) {
                        $query = "INSERT INTO issue_custom_field_data(issue_id, field_id, `value`, date_created) VALUES (?, ?, ?, ?)";

                        $fieldValue = $value[$i];

                        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                        $stmt->bind_param("iiss", $issueId, $fieldId, $fieldValue, $currentDate);
                        $stmt->execute();
                    }
                } else {
                    $valueField = CustomField::getCustomFieldsDataByFieldId($issueId, $fieldId);

                    if ($valueField) {
                        $query = "update issue_custom_field_data set `value` = ?, date_updated = ? where issue_id = ? and field_id = ? limit 1";

                        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                        $stmt->bind_param("ssii", $value, $currentDate, $issueId, $fieldId);
                        $stmt->execute();
                    } else {
                        // insert the value
                        $query = "INSERT INTO issue_custom_field_data(issue_id, field_id, `value`, date_created) VALUES (?, ?, ?, ?)";

                        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                        $stmt->bind_param("iiss", $issueId, $fieldId, $value, $currentDate);
                        $stmt->execute();
                    }
                }
            } else {
                $query = "delete from issue_custom_field_data where issue_id = ? and field_id = ? limit 1";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("ii", $issueId, $fieldId);
                $stmt->execute();
            }
        }
    }

    public static function getCustomFieldsDataByFieldId($issueId, $fieldId) {
        $query = 'SELECT issue_custom_field_data.value, field.name, field.sys_field_type_id ' .
            'FROM issue_custom_field_data ' .
            'LEFT JOIN field on field.id = issue_custom_field_data.field_id ' .
            'WHERE issue_id = ? and field_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueId, $fieldId);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public static function deleteCustomFieldsData($issueId) {
        $query = 'delete from issue_custom_field_data where issue_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }

    public static function getUserPickerData($issueId, $fieldId = null) {
        $queryWhere = '';

        if ($fieldId) {
            $queryWhere = ' and issue_custom_field_data.field_id = ' . $fieldId;
        }

        $query = 'SELECT user.id, user.first_name, user.last_name, field.name, sys_field_type.code, issue_custom_field_data.field_id ' .
            'FROM issue_custom_field_data ' .
            'LEFT JOIN field on field.id = issue_custom_field_data.field_id ' .
            'left join sys_field_type on sys_field_type.id = field.sys_field_type_id ' .
            'left join user on user.id = issue_custom_field_data.value ' .
            'WHERE issue_id = ? and ' .
            'sys_field_type.id IN (7) ' . $queryWhere . ' ' .
            'order by field.name';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows) {
            $resultData = array();
            while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                if (!array_key_exists($data['field_id'], $resultData)) {
                    $resultData[$data['field_id']] = array();
                }
                array_push($resultData[$data['field_id']], array('user_id' => $data['id'],
                                                             'first_name' => $data['first_name'],
                                                             'last_name' => $data['last_name'],
                                                             'field_name' => $data['name'],
                                                             'field_code' => $data['code']));
            }
            return $resultData;
        } else
            return null;
    }
}
