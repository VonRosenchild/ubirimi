<?php

namespace Ubirimi\Yongo\Repository\Workflow;

use Ubirimi\Container\UbirimiContainer;

class WorkflowPosition {

    public static function getByWorkflowId($Id) {
        $query = "select * " .
            "from workflow_position " .
            "where workflow_position.workflow_id = " . $Id;

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function deleteByWorkflowId($workflowId) {
        $query = "delete from workflow_position where workflow_id = ? ";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $workflowId);
            $stmt->execute();
        }
    }

    public static function addSinglePositionRecord($workflowId, $stepId, $topPosition, $leftPosition) {

        $q = 'insert into workflow_position(workflow_id, workflow_step_id, top_position, left_position) values(?, ?, ?, ?)';
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($q)) {
            $stmt->bind_param("iiii", $workflowId, $stepId, $topPosition, $leftPosition);

            $stmt->execute();
        }
    }

    public static function addPosition($workflowId, $data) {
        for ($i = 0; $i < count($data); $i++) {
            $q = 'insert into workflow_position(workflow_id, workflow_step_id, top_position, left_position) ' .
                'values(?, ?, ?, ?)';
            if ($stmt = UbirimiContainer::get()['db.connection']->prepare($q)) {
                $stmt->bind_param("iiii", $workflowId, $data[$i][0], $data[$i][1], $data[$i][2]);

                $stmt->execute();
            }
        }
    }
}