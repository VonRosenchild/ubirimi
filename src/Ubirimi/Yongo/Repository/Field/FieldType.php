<?php

namespace Ubirimi\Yongo\Repository\Field;

use Ubirimi\Container\UbirimiContainer;

class FieldType
{
    public static function getByCode($code) {
        $query = "SELECT * from sys_field_type where code = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }
}
