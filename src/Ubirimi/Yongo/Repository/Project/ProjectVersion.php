<?php

namespace Ubirimi\Yongo\Repository\Project;

use Ubirimi\Container\UbirimiContainer;

class ProjectVersion {

    public static function getByIds($Ids) {
        $query = 'SELECT project_version.* ' .
            'FROM project_version ' .
            'WHERE id IN (' . implode(', ', $Ids) . ') ';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }
}