<?php

    namespace Ubirimi\Yongo\Repository\Issue;

    use Ubirimi\Container\UbirimiContainer;

    class IssueCustomField {

        public static function saveCustomFieldsData($issueId, $issueCustomFieldsData, $currentDate) {

            foreach ($issueCustomFieldsData as $key => $value) {
                $keyData = explode("_", $key);
                $fieldId = $keyData[0];

                if ($value != '' && $value != null) {
                    $query = "INSERT INTO issue_custom_field_data(issue_id, field_id, `value`, date_created) VALUES (?, ?, ?, ?)";

                    if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
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
                'WHERE issue_id = ?';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("i", $issueId);
                $stmt->execute();

                $result = $stmt->get_result();
                if ($result->num_rows)
                    return $result;
                else
                    return null;
            }
        }

        public static function updateCustomFieldsData($issueId, $issueCustomFieldsData, $currentDate) {

            foreach ($issueCustomFieldsData as $key => $value) {
                $keyData = explode("_", $key);
                $fieldId = $keyData[0];
                if (!empty($value)) {
                    $valueField = IssueCustomField::getCustomFieldsDataByFieldId($issueId, $fieldId);

                    if ($valueField) {
                        $query = "update issue_custom_field_data set `value` = ?, date_updated = ? where issue_id = ? and field_id = ? limit 1";

                        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                            $stmt->bind_param("ssii", $value, $currentDate, $issueId, $fieldId);
                            $stmt->execute();
                        }

                    } else {
                        // insert the value
                        $query = "INSERT INTO issue_custom_field_data(issue_id, field_id, `value`, date_created) VALUES (?, ?, ?, ?)";

                        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                            $stmt->bind_param("iiss", $issueId, $fieldId, $value, $currentDate);
                            $stmt->execute();
                        }
                    }
                } else {
                    $query = "delete from issue_custom_field_data where issue_id = ? and field_id = ? limit 1";

                    if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                        $stmt->bind_param("ii", $issueId, $fieldId);
                        $stmt->execute();
                    }
                }
            }
        }

        public static function getCustomFieldsDataByFieldId($issueId, $fieldId) {
            $query = 'SELECT issue_custom_field_data.value, field.name ' .
                'FROM issue_custom_field_data ' .
                'LEFT JOIN field on field.id = issue_custom_field_data.field_id ' .
                'WHERE issue_id = ? and field_id = ? ' .
                "limit 1";

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("ii", $issueId, $fieldId);
                $stmt->execute();

                $result = $stmt->get_result();
                if ($result->num_rows)
                    return $result->fetch_array(MYSQLI_ASSOC);
                else
                    return null;
            }
        }

        public static function deleteCustomFieldsData($issueId) {
            $query = 'delete from issue_custom_field_data where issue_id = ?';

            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                $stmt->bind_param("i", $issueId);
                $stmt->execute();
            }
        }
    }
