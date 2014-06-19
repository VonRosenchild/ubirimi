<?php

namespace Ubirimi\Yongo\Repository\Workflow;

use Ubirimi\Container\UbirimiContainer;

class WorkflowCondition {

    const CONDITION_ONLY_ASSIGNEE = 1;
    const CONDITION_ONLY_REPORTER = 2;
    const CONDITION_PERMISSION = 3;
    public static function getByTransitionId($workflowDataId) {
        $query = "select workflow_condition_data.* " .
            "from workflow_condition_data " .
            "where workflow_condition_data.workflow_data_id = ? " .
            "limit 1";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $workflowDataId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function deleteByTransitionId($transitionId) {
        $q = 'delete from workflow_condition_data where workflow_data_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($q)) {
            $stmt->bind_param("i", $transitionId);
            $stmt->execute();
        }
    }

    public static function getAll() {
        $query = "SELECT * from sys_condition";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function addConditionString($transitionId, $stringText) {
        $q = 'update workflow_condition_data set definition_data = CONCAT(definition_data, ?) where workflow_data_id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($q)) {
            $stmt->bind_param("si", $stringText, $transitionId);
            $stmt->execute();
        }
    }
}