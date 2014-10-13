<?php

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;

class SystemOperation
{
    const OPERATION_CREATE = 1;
    const OPERATION_EDIT = 2;
    const OPERATION_VIEW = 3;

    public function getAll() {
        $query = "SELECT * " .
            "FROM sys_operation";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }
}
