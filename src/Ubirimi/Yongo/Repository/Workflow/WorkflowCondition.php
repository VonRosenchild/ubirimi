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

class WorkflowCondition
{
    const CONDITION_ONLY_ASSIGNEE = 1;
    const CONDITION_ONLY_REPORTER = 2;
    const CONDITION_PERMISSION = 3;

    public function getByTransitionId($workflowDataId) {
        $query = "select workflow_condition_data.* " .
            "from workflow_condition_data " .
            "where workflow_condition_data.workflow_data_id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $workflowDataId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function deleteByTransitionId($transitionId) {
        $q = 'delete from workflow_condition_data where workflow_data_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($q);
        $stmt->bind_param("i", $transitionId);
        $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT * from sys_condition";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function addConditionString($transitionId, $stringText) {
        $q = 'update workflow_condition_data set definition_data = CONCAT(definition_data, ?) where workflow_data_id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($q);
        $stmt->bind_param("si", $stringText, $transitionId);
        $stmt->execute();
    }
}
