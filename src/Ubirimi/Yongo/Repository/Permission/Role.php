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

class Role
{
    public function addDefaultPermissionRoles($clientId, $date) {
        $query = 'insert into permission_role(client_id, name, description, date_created) values ';

        $query .= "(" . $clientId . ", 'Administrators', 'The Administrator has all the privileges set', '" . $date . "')";
        $query .= ",(" . $clientId . ", 'Developers', 'The Developer has a basic set of privileges, mainly related to issues', '" . $date . "')";
        $query .= ",(" . $clientId . ", 'Users', 'Users', '" . $date . "')";

        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function getByClient($clientId) {
        $query = 'SELECT * ' .
            'FROM permission_role ' .
            'WHERE client_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getById($Id) {
        $query = 'SELECT * ' .
            'FROM permission_role ' .
            'WHERE id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function addProjectRoleForUser($userId, $projectId, $roleId, $currentDate) {
        $query = "INSERT INTO project_role_data(project_id, permission_role_id, user_id, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiis", $projectId, $roleId, $userId, $currentDate);
        $stmt->execute();
    }

    public function deleteRolesForUser($userId) {
        $query = "delete from project_role_data where user_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    public function getPermissionRoleById($permissionRoleId) {
        $query = 'select id, client_id, name, description ' .
                 'from permission_role ' .
                 'where id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $permissionRoleId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function deleteDefaultUsersByPermissionRoleId($permissionRoleId) {
        $query = 'delete from permission_role_data where permission_role_id = ? and default_user_id is not null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $permissionRoleId);
        $stmt->execute();
    }

    public function deleteDefaultGroupsByPermissionRoleId($permissionRoleId) {
        $query = 'delete from permission_role_data where permission_role_id = ? and default_group_id is not null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $permissionRoleId);
        $stmt->execute();
    }

    public function add($clientId, $name, $description, $date) {
        $query = "INSERT INTO permission_role(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("isss", $clientId, $name, $description, $date);
        $stmt->execute();
    }

    public function updateById($permissionRoleId, $name, $description, $date) {
        $query = 'update permission_role set name = ?, description = ?, date_updated = ? where id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $permissionRoleId);
        $stmt->execute();
    }

    public function getDefaultUsers($permissionRoleId) {
        $query = 'select general_user.id as user_id, general_user.first_name, general_user.last_name ' .
            'from permission_role_data ' .
            'left join general_user on general_user.id = permission_role_data.default_user_id ' .
            'where permission_role_data.permission_role_id = ? and ' .
            'permission_role_data.default_user_id is not null ' .
            'order by general_user.first_name, general_user.last_name';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $permissionRoleId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getDefaultGroups($permissionRoleId) {
        $query = 'SELECT general_group.id as group_id, general_group.name as group_name ' .
            'from permission_role_data ' .
            'left join `general_group` on general_group.id = permission_role_data.default_group_id ' .
            'where permission_role_data.permission_role_id = ? and ' .
            'permission_role_data.default_group_id is not null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $permissionRoleId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function addDefaultUsers($permissionRoleId, $userArray, $currentDate) {
        $query = 'insert into permission_role_data(permission_role_id, default_user_id, date_created) values ';

        for ($i = 0; $i < count($userArray); $i++)
            $query .= '(' . $permissionRoleId . ' ,' . $userArray[$i] . ",'" . $currentDate . "'), ";

        $query = substr($query, 0, strlen($query) - 2);

        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function addDefaultGroups($permissionRoleId, $groupArrayIds, $currentDate) {
        $query = 'insert into permission_role_data(permission_role_id, default_group_id, date_created) values ';

        for ($i = 0; $i < count($groupArrayIds); $i++)
            $query .= '(' . $permissionRoleId . ' ,' . $groupArrayIds[$i] . ",'" . $currentDate . "'), ";

        $query = substr($query, 0, strlen($query) - 2);

        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function deleteById($permissionRoleId) {
        $query = 'delete from permission_role_data where permission_role_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $permissionRoleId);
        $stmt->execute();

        $query = 'delete from permission_role where id = ? limit 1';
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $permissionRoleId);
        $stmt->execute();
    }

    public function getByName($clientId, $name, $roleId = null) {
        $query = 'select id, name from permission_role where client_id = ? and LOWER(name)= LOWER(?) ';
        if ($roleId) $query .= 'and id != ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($roleId) $stmt->bind_param("isi", $clientId, $name, $roleId);
        else
            $stmt->bind_param("is", $clientId, $name);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }
}
