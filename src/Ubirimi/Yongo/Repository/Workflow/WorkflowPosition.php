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

namespace Ubirimi\Yongo\Repository\Workflow;

use Ubirimi\Container\UbirimiContainer;

class WorkflowPosition
{
    public function getByWorkflowId($Id) {
        $query = "select * " .
            "from workflow_position " .
            "where workflow_position.workflow_id = " . $Id;

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteByWorkflowId($workflowId) {
        $query = "delete from workflow_position where workflow_id = ? ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $workflowId);
        $stmt->execute();
    }

    public function addSinglePositionRecord($workflowId, $stepId, $topPosition, $leftPosition) {
        $q = 'insert into workflow_position(workflow_id, workflow_step_id, top_position, left_position) values(?, ?, ?, ?)';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($q);
        $stmt->bind_param("iiii", $workflowId, $stepId, $topPosition, $leftPosition);

        $stmt->execute();
    }

    public function addPosition($workflowId, $data) {
        for ($i = 0; $i < count($data); $i++) {
            $q = 'insert into workflow_position(workflow_id, workflow_step_id, top_position, left_position) ' .
                'values(?, ?, ?, ?)';

            $stmt = UbirimiContainer::get()['db.connection']->prepare($q);
            $stmt->bind_param("iiii", $workflowId, $data[$i][0], $data[$i][1], $data[$i][2]);

            $stmt->execute();
        }
    }
}
