<?php

namespace Ubirimi\Yongo\Repository\Permission;
use Ubirimi\Container\UbirimiContainer;

class PermissionRole {

    public static function addDefaultPermissionRoles($clientId, $date) {
        $query = 'insert into permission_role(client_id, name, description, date_created) values ';

        $query .= "(" . $clientId . ", 'Administrators', 'The Administrator has all the privileges set', '" . $date . "')";
        $query .= ",(" . $clientId . ", 'Developers', 'The Developer has a basic set of privileges, mainly related to issues', '" . $date . "')";
        $query .= ",(" . $clientId . ", 'Users', 'Users', '" . $date . "')";

        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function getByClient($clientId) {
        $query = 'SELECT * ' .
            'FROM permission_role ' .
            'WHERE client_id = ? ';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $clientId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getById($Id) {
        $query = 'SELECT * ' .
            'FROM permission_role ' .
            'WHERE id = ? ';

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

    public static function addProjectRoleForUser($userId, $projectId, $roleId, $currentDate) {
        $query = "INSERT INTO project_role_data(project_id, permission_role_id, user_id, date_created) VALUES (?, ?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("iiis", $projectId, $roleId, $userId, $currentDate);
            $stmt->execute();
        }
    }

    public static function deleteRolesForUser($userId) {
        $query = "delete from project_role_data where user_id = ?";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->bind_param("i", $userId);
            $stmt->execute();
        }
    }

    public static function getPermissionRoleById($permissionRoleId) {
        $query = 'select id, client_id, name, description ' .
                 'from permission_role ' .
                 'where id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $permissionRoleId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result->fetch_array(MYSQLI_ASSOC);
            else
                return null;
        }
    }

    public static function deleteDefaultUsersByPermissionRoleId($permissionRoleId) {
        $query = 'delete from permission_role_data where permission_role_id = ? and default_user_id is not null';
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $permissionRoleId);
            $stmt->execute();
        }
    }

    public static function deleteDefaultGroupsByPermissionRoleId($permissionRoleId) {
        $query = 'delete from permission_role_data where permission_role_id = ? and default_group_id is not null';
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $permissionRoleId);
            $stmt->execute();
        }
    }

    public static function add($clientId, $name, $description, $date) {
        $query = "INSERT INTO permission_role(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("isss", $clientId, $name, $description, $date);
            $stmt->execute();
        }
    }

    public static function updateById($permissionRoleId, $name, $description, $date) {
        $query = 'update permission_role set name = ?, description = ?, date_updated = ? where id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("sssi", $name, $description, $date, $permissionRoleId);
            $stmt->execute();
        }
    }

    public static function getDefaultUsers($permissionRoleId) {
        $query = 'select user.id as user_id, user.first_name, user.last_name ' .
            'from permission_role_data ' .
            'left join user on user.id = permission_role_data.default_user_id ' .
            'where permission_role_data.permission_role_id = ? and ' .
            'permission_role_data.default_user_id is not null ' .
            'order by user.first_name, user.last_name';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $permissionRoleId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function getDefaultGroups($permissionRoleId) {
        $query = 'select group.id as group_id, group.name as group_name ' .
            'from permission_role_data ' .
            'left join `group` on group.id = permission_role_data.default_group_id ' .
            'where permission_role_data.permission_role_id = ? and ' .
            'permission_role_data.default_group_id is not null';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $permissionRoleId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows)
                return $result;
            else
                return null;
        }
    }

    public static function addDefaultUsers($permissionRoleId, $userArray, $currentDate) {
        $query = 'insert into permission_role_data(permission_role_id, default_user_id, date_created) values ';

        for ($i = 0; $i < count($userArray); $i++)
            $query .= '(' . $permissionRoleId . ' ,' . $userArray[$i] . ",'" . $currentDate . "'), ";

        $query = substr($query, 0, strlen($query) - 2);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function addDefaultGroups($permissionRoleId, $groupArrayIds, $currentDate) {
        $query = 'insert into permission_role_data(permission_role_id, default_group_id, date_created) values ';

        for ($i = 0; $i < count($groupArrayIds); $i++)
            $query .= '(' . $permissionRoleId . ' ,' . $groupArrayIds[$i] . ",'" . $currentDate . "'), ";

        $query = substr($query, 0, strlen($query) - 2);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public static function deleteById($permissionRoleId) {
        $query = 'delete from permission_role_data where permission_role_id = ?';
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $permissionRoleId);
            $stmt->execute();
        }

        $query = 'delete from permission_role where id = ? limit 1';
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $permissionRoleId);
            $stmt->execute();
        }
    }

    public static function getByName($clientId, $name, $roleId = null) {
        $query = 'select id, name from permission_role where client_id = ? and LOWER(name)= LOWER(?) ';
        if ($roleId) $query .= 'and id != ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
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
}
