<?php

namespace Ubirimi\Yongo\Repository\Permission;

use Ubirimi\Container\UbirimiContainer;

class GlobalPermission {

    const GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS = 1;
    const GLOBAL_PERMISSION_YONGO_ADMINISTRATORS = 2;
    const GLOBAL_PERMISSION_YONGO_USERS = 3;
    const GLOBAL_PERMISSION_YONGO_BULK_CHANGE = 4;
    const GLOBAL_PERMISSION_DOCUMENTADOR_ADMINISTRATOR = 5;
    const GLOBAL_PERMISSION_DOCUMENTADOR_SYSTEM_ADMINISTRATOR = 6;
    const GLOBAL_PERMISSION_DOCUMENTADOR_CREATE_SPACE = 7;

    public static function getAllByProductId($productId) {
        $query = "SELECT * FROM sys_permission_global where sys_product_id = ? order by name";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getById($permissionId) {
        $query = "SELECT * FROM sys_permission_global where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $permissionId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function getDataByPermissionIdAndUserId($clientId, $globalPermissionId, $userId) {
        $query = 'select `group`.name, `group`.id, sys_permission_global_data.id as sys_permission_global_data_id ' .
            'from sys_permission_global_data ' .
            'left join `group` on group.id = sys_permission_global_data.group_id ' .
            'where sys_permission_global_data.sys_permission_global_id = ? and ' .
            'sys_permission_global_data.client_id = ? and ' .
            'sys_permission_global_data.user_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iii", $globalPermissionId, $clientId, $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getDataByPermissionIdAndGroupId($clientId, $globalPermissionId, $groupId) {
        $query = 'select `group`.name, `group`.id, sys_permission_global_data.id as sys_permission_global_data_id ' .
            'from sys_permission_global_data ' .
            'left join `group` on group.id = sys_permission_global_data.group_id ' .
            'where sys_permission_global_data.sys_permission_global_id = ? and ' .
            'sys_permission_global_data.client_id = ? and ' .
            'sys_permission_global_data.group_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iii", $globalPermissionId, $clientId, $groupId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getDataByPermissionId($clientId, $globalPermissionId, $resultType = null, $resultColumn = null) {
        $query = 'select `group`.name, `group`.id, sys_permission_global_data.id as sys_permission_global_data_id ' .
            'from sys_permission_global_data ' .
            'left join `group` on group.id = sys_permission_global_data.group_id ' .
            'where sys_permission_global_data.sys_permission_global_id = ? and ' .
            'sys_permission_global_data.client_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $globalPermissionId, $clientId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                if ($resultType == 'array') {
                    $resultArray = array();
                    while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                        if ($resultColumn)
                            $resultArray[] = $data[$resultColumn];
                        else
                            $resultArray[] = $data;
                    }

                   return $resultArray;
                } else
                    return $result;
            } else
                return null;
        }
    }

    public static function addDataForGroupId($clientId, $permissionId, $groupId, $date) {
        $query = "INSERT INTO sys_permission_global_data(client_id, sys_permission_global_id, group_id, date_created) VALUES " .
                 "(?, ?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iiis", $clientId, $permissionId, $groupId, $date);
            $stmt->execute();
            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function addDataForUserId($clientId, $permissionId, $userId) {
        $query = "INSERT INTO sys_permission_global_data(client_id, sys_permission_global_id, user_id) VALUES " .
            "(?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iii", $clientId, $permissionId, $userId);
            $stmt->execute();
            return UbirimiContainer::get()['db.connection']->insert_id;
        }
    }

    public static function deleteById($Id) {
        $query = "delete from sys_permission_global_data where id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $Id);
            $stmt->execute();
        }
    }

    public static function deleteByPermissionId($clientId, $globalsPermissionId, $type) {
        $query = "delete from sys_permission_global_data where client_id = ? and sys_permission_global_id = ? ";

        if ($type == 'group')
            $query .= 'and group_id is not null';
        else if ($type == 'user')
            $query .= 'and user_id is not null';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $clientId, $globalsPermissionId);
            $stmt->execute();
        }
    }

    public static function getDataById($Id) {
        $query = 'select `group`.name, `group`.id, sys_permission_global_data.id as sys_permission_global_data_id, ' .
            'sys_permission_global.name as permission_name ' .
            'from sys_permission_global_data ' .
            'left join sys_permission_global on sys_permission_global.id = sys_permission_global_data.sys_permission_global_id ' .
            'left join `group` on group.id = sys_permission_global_data.group_id ' .
            'where sys_permission_global_data.id = ? ' .
            'limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $Id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }
}