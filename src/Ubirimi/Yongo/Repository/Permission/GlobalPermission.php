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

namespace Ubirimi\Yongo\Repository\Permission;

use Ubirimi\Container\UbirimiContainer;

class GlobalPermission
{
    const GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS = 1;
    const GLOBAL_PERMISSION_YONGO_ADMINISTRATORS = 2;
    const GLOBAL_PERMISSION_YONGO_USERS = 3;
    const GLOBAL_PERMISSION_YONGO_BULK_CHANGE = 4;
    const GLOBAL_PERMISSION_DOCUMENTADOR_ADMINISTRATOR = 5;
    const GLOBAL_PERMISSION_DOCUMENTADOR_SYSTEM_ADMINISTRATOR = 6;
    const GLOBAL_PERMISSION_DOCUMENTADOR_CREATE_SPACE = 7;

    public function getAllByProductId($productId) {
        $query = "SELECT * FROM sys_permission_global where sys_product_id = ? order by name";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getById($permissionId) {
        $query = "SELECT * FROM sys_permission_global where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $permissionId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getDataByPermissionIdAndUserId($clientId, $globalPermissionId, $userId) {
        $query = 'select `general_group`.name, `general_group`.id, sys_permission_global_data.id as sys_permission_global_data_id ' .
            'from sys_permission_global_data ' .
            'left join `general_group` on general_group.id = sys_permission_global_data.group_id ' .
            'where sys_permission_global_data.sys_permission_global_id = ? and ' .
            'sys_permission_global_data.client_id = ? and ' .
            'sys_permission_global_data.user_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $globalPermissionId, $clientId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getDataByPermissionIdAndGroupId($clientId, $globalPermissionId, $groupId) {
        $query = 'select `general_group`.name, `general_group`.id, sys_permission_global_data.id as sys_permission_global_data_id ' .
            'from sys_permission_global_data ' .
            'left join `general_group` on general_group.id = sys_permission_global_data.group_id ' .
            'where sys_permission_global_data.sys_permission_global_id = ? and ' .
            'sys_permission_global_data.client_id = ? and ' .
            'sys_permission_global_data.group_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $globalPermissionId, $clientId, $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getDataByPermissionId($clientId, $globalPermissionId, $resultType = null, $resultColumn = null) {
        $query = 'select `general_group`.name, `general_group`.id, sys_permission_global_data.id as sys_permission_global_data_id ' .
            'from sys_permission_global_data ' .
            'left join `general_group` on general_group.id = sys_permission_global_data.group_id ' .
            'where sys_permission_global_data.sys_permission_global_id = ? and ' .
            'sys_permission_global_data.client_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
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
        }

        return null;
    }

    public function addDataForGroupId($clientId, $permissionId, $groupId, $date) {
        $query = "INSERT INTO sys_permission_global_data(client_id, sys_permission_global_id, group_id, date_created) VALUES " .
                 "(?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiis", $clientId, $permissionId, $groupId, $date);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function addDataForUserId($clientId, $permissionId, $userId) {
        $query = "INSERT INTO sys_permission_global_data(client_id, sys_permission_global_id, user_id) VALUES " .
            "(?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $clientId, $permissionId, $userId);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function deleteById($Id) {
        $query = "delete from sys_permission_global_data where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public function deleteByPermissionId($clientId, $globalsPermissionId, $type) {
        $query = "delete from sys_permission_global_data where client_id = ? and sys_permission_global_id = ? ";

        if ($type == 'group')
            $query .= 'and group_id is not null';
        else if ($type == 'user')
            $query .= 'and user_id is not null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $globalsPermissionId);
        $stmt->execute();
    }

    public function getDataById($Id) {
        $query = 'select `general_group`.name, `general_group`.id, sys_permission_global_data.id as sys_permission_global_data_id, ' .
            'sys_permission_global.name as permission_name ' .
            'from sys_permission_global_data ' .
            'left join sys_permission_global on sys_permission_global.id = sys_permission_global_data.sys_permission_global_id ' .
            'left join `general_group` on general_group.id = sys_permission_global_data.group_id ' .
            'where sys_permission_global_data.id = ? ' .
            'limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }
}
