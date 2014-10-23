<?php

namespace Ubirimi\Yongo\Repository\Field;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\Type;
use Ubirimi\Yongo\Repository\Project\Project;

class Custom {

    public function getById($Id) {
        $query = "SELECT * FROM field where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getAllByClient($clientId) {
        $query = "SELECT field.id, field.name, field.description, sys_field_type.name as type_name, sys_field_type.description as type_description, " .
                 "field.all_issue_type_flag, field.all_project_flag " .
            "FROM field " .
            "left join sys_field_type on sys_field_type.id = field.sys_field_type_id " .
            "where system_flag = 0 and client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getTypes() {
        $query = "SELECT * FROM sys_field_type";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function create($clientId, $fieldType, $name, $description, $issueType, $project, $date) {
        $query = "INSERT INTO field(client_id, sys_field_type_id, name, description, system_flag, all_issue_type_flag, all_project_flag, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $systemFlag = 0;
        $fieldTypeResult = Type::getByCode($fieldType);
        $fieldTypeId = $fieldTypeResult['id'];

        $allIssueTypeFlag = (count($issueType) == 1 && $issueType[0] == -1) ? 1 : 0;
        $allProjectFlag = (count($project) == 1 && $project[0] == -1) ? 1 : 0;

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iissiiis", $clientId, $fieldTypeId, $name, $description, $systemFlag, $allIssueTypeFlag, $allProjectFlag, $date);
        $stmt->execute();

        $fieldId = UbirimiContainer::get()['db.connection']->insert_id;

        // add data if necessary

        if ($allIssueTypeFlag) {
            $issueTypeResult = Type::getAll($clientId);
            $issueType = array();
            while ($type = $issueTypeResult->fetch_array(MYSQLI_ASSOC)) {
                $issueType[] = $type['id'];
            }
        }

        for ($i = 0; $i < count($issueType); $i++) {
            $queryIssueType = "INSERT INTO field_issue_type_data(field_id, issue_type_id) VALUES (?, ?)";
            if ($stmtIssueType = UbirimiContainer::get()['db.connection']->prepare($queryIssueType)) {

                $stmtIssueType->bind_param("ii", $fieldId, $issueType[$i]);
                $stmtIssueType->execute();
            }
        }

        if ($allProjectFlag) {
            $projectResult = UbirimiContainer::get()['repository']->get('yongo.project.project')->getByClientId($clientId);
            $project = array();
            while ($pr = $projectResult->fetch_array(MYSQLI_ASSOC)) {
                $project[] = $pr['id'];
            }
        }
        for ($i = 0; $i < count($project); $i++) {
            $queryProject = "INSERT INTO field_project_data(field_id, project_id) VALUES (?, ?)";
            if ($stmtProject = UbirimiContainer::get()['db.connection']->prepare($queryProject)) {

                $stmtProject->bind_param("ii", $fieldId, $project[$i]);
                $stmtProject->execute();
            }
        }

        return $fieldId;
    }

    public function updateMetaDataById($Id, $name, $description, $date) {
        $query = "update field set name = ?, description = ?, date_updated = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $Id);
        $stmt->execute();
    }

    public function deleteDataByProjectId($projectId) {
        $query = "delete from field_project_data where project_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
    }

    public function deleteById($customFieldId) {
        $query = "delete from field where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $customFieldId);
        $stmt->execute();

        $query = "delete from field_configuration_data where field_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $customFieldId);
        $stmt->execute();

        $query = "delete from field_issue_type_data where field_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $customFieldId);
        $stmt->execute();

        $query = "delete from field_project_data where field_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $customFieldId);
        $stmt->execute();

        $query = "delete from issue_custom_field_data where field_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $customFieldId);
        $stmt->execute();

        $query = "delete from screen_data where field_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $customFieldId);
        $stmt->execute();
    }

    public function getByNameAndType($clientId, $name, $fieldType) {
        $query = "SELECT * FROM field where client_id = ? and sys_field_type_id = ? and name = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iis", $clientId, $fieldType, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }
}
