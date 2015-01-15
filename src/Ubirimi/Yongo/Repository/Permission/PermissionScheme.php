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

class PermissionScheme
{
    private $name;
    private $description;
    private $clientId;

    function __construct($clientId = null, $name = null, $description = null) {
        $this->clientId = $clientId;
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public function save($currentDate) {
        $query = "INSERT INTO permission_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isss", $this->clientId, $this->name, $this->description, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getByClientId($clientId) {
        $query = "select * from permission_scheme where client_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getMetaDataById($Id) {
        $query = "select * " .
            "from permission_scheme " .
            "where id = ? " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function updateMetaDataById($Id, $name, $description, $date) {
        $query = "update permission_scheme set name = ?, description = ?, date_updated = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $Id);
        $stmt->execute();
    }

    public function getDataByPermissionId($permissionSchemeId, $permissionId) {
        $query = "select permission_scheme_data.id, general_user.id as user_id, general_user.first_name, general_user.last_name, permission_scheme_data.reporter, " .
                    "permission_scheme_data.group_id as group_id, general_group.name as group_name, " .
                    "permission_role.id as permission_role_id, permission_role.name as permission_role_name, " .
                    "permission_scheme_data.current_assignee, permission_scheme_data.reporter, permission_scheme_data.project_lead " .
            "from permission_scheme_data " .
            "left join permission_role on permission_role.id = permission_scheme_data.permission_role_id " .
            "left join `general_group` on general_group.id = permission_scheme_data.group_id " .
            "left join general_user on general_user.id = permission_scheme_data.user_id " .
            "where permission_scheme_data.permission_scheme_id = ? " .
            "and permission_scheme_data.sys_permission_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $permissionSchemeId, $permissionId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getDataByPermissionSchemeId($permissionSchemeId) {
        $query = "select permission_scheme_data.* " .
            "from permission_scheme_data " .
            "where permission_scheme_data.permission_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $permissionSchemeId);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getDataByPermissionSchemeIdAndPermissionId($permissionSchemeId, $sysPermissionId) {
        $query = "select permission_scheme_data.* " .
            "from permission_scheme_data " .
            "where permission_scheme_data.permission_scheme_id = ? and sys_permission_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $permissionSchemeId, $sysPermissionId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getDataByProjectIdAndPermissionId($projectId, $sysPermissionId) {
        $query = "select permission_scheme_data.* " .
            "from permission_scheme_data " .
            "left join permission_scheme on permission_scheme.id = permission_scheme_data.permission_scheme_id " .
            "left join project on project.permission_scheme_id = permission_scheme.id " .
            "where project.id = ? and sys_permission_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $projectId, $sysPermissionId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getMetaDataByNameAndClientId($clientId, $name) {
        $query = "select * from permission_scheme where client_id = ? and LOWER(name) = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $clientId, $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function addData($permissionSchemeId, $sysPermissionId, $permissionType, $permissionRoleId, $groupId, $userId, $currentDate) {
        switch ($permissionType) {

            case Permission::PERMISSION_TYPE_USER:
                $query = "INSERT INTO permission_scheme_data(permission_scheme_id, sys_permission_id, user_id, date_created) VALUES (?, ?, ?, ?)";
                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

                $stmt->bind_param("iiis", $permissionSchemeId, $sysPermissionId, $userId, $currentDate);
                $stmt->execute();

                return UbirimiContainer::get()['db.connection']->insert_id;

                break;

            case Permission::PERMISSION_TYPE_GROUP:
                $query = "INSERT INTO permission_scheme_data(permission_scheme_id, sys_permission_id, group_id, date_created) VALUES (?, ?, ?, ?)";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

                $stmt->bind_param("iiis", $permissionSchemeId, $sysPermissionId, $groupId, $currentDate);
                $stmt->execute();

                return UbirimiContainer::get()['db.connection']->insert_id;

                break;

            case Permission::PERMISSION_TYPE_PROJECT_ROLE:
                $query = "INSERT INTO permission_scheme_data(permission_scheme_id, sys_permission_id, permission_role_id, date_created) VALUES (?, ?, ?, ?)";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

                $stmt->bind_param("iiis", $permissionSchemeId, $sysPermissionId, $permissionRoleId, $currentDate);
                $stmt->execute();

                return UbirimiContainer::get()['db.connection']->insert_id;

                break;

            case Permission::PERMISSION_TYPE_CURRENT_ASSIGNEE:
            case Permission::PERMISSION_TYPE_REPORTER:
            case Permission::PERMISSION_TYPE_PROJECT_LEAD:

                $query = "INSERT INTO permission_scheme_data(permission_scheme_id, sys_permission_id, `" . $permissionType . "`, date_created) VALUES (?, ?, ?, ?)";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $value = 1;
                $stmt->bind_param("iiis", $permissionSchemeId, $sysPermissionId, $value, $currentDate);
                $stmt->execute();

                return UbirimiContainer::get()['db.connection']->insert_id;

                break;
        }
    }

    public function getUsersForPermissionId($permissionSchemeId, $permissionId) {
        $query = "select general_user.id as user_id, general_user.first_name, general_user.last_name " .
            "from permission_scheme_data " .
            "left join general_user on general_user.id = permission_scheme_data.user_id " .
            "where permission_scheme_data.permission_scheme_id = ? and permission_scheme_data.sys_permission_id = ? and user_id is not null";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $permissionSchemeId, $permissionId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getGroupsForPermissionId($permissionSchemeId, $permissionId) {
        $query = "SELECT general_group.id as group_id, general_group.name as group_name " .
            "from permission_scheme_data " .
            "left join `general_group` on general_group.id = permission_scheme_data.group_id " .
            "where permission_scheme_data.permission_scheme_id = ? and permission_scheme_data.sys_permission_id = ? and group_id is not null";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $permissionSchemeId, $permissionId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getPermissionRolesForPermissionId($permissionSchemeId, $permissionId) {
        $query = "select permission_role.id as permission_role_id, permission_role.name as permission_role_name " .
            "from permission_scheme_data " .
            "left join permission_role on permission_role.id = permission_scheme_data.permission_role_id " .
            "where permission_scheme_data.permission_scheme_id = ? and permission_scheme_data.sys_permission_id = ? and permission_role_id is not null";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $permissionSchemeId, $permissionId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteUserDataByPermissionId($permissionSchemeId, $permissionId) {
        $query = "delete from permission_scheme_data where permission_scheme_id = ? and sys_permission_id = ? and user_id is not null";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $permissionSchemeId, $permissionId);
        $stmt->execute();
    }

    public function deleteGroupDataByPermissionId($permissionSchemeId, $permissionId) {
        $query = "delete from permission_scheme_data where permission_scheme_id = ? and sys_permission_id = ? and group_id is not null";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $permissionSchemeId, $permissionId);
        $stmt->execute();
    }

    public function deleteRoleDataByPermissionId($permissionSchemeId, $permissionId) {
        $query = "delete from permission_scheme_data where permission_scheme_id = ? and sys_permission_id = ? and permission_role_id is not null";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $permissionSchemeId, $permissionId);
        $stmt->execute();
    }

    public function addUserDataToPermissionId($permissionSchemeId, $permissionId, $userArray) {
        $query = 'insert into permission_scheme_data(permission_scheme_id, sys_permission_id, user_id) values ';
        for ($i = 0; $i < count($userArray); $i++)
            $query .= '(' . $permissionSchemeId . ', ' . $permissionId . ', ' . $userArray[$i] . '),';

        $query = substr($query, 0, strlen($query) - 1);

        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function addGroupDataToPermissionId($permissionSchemeId, $permissionId, $groupArr) {
        $query = 'insert into permission_scheme_data(permission_scheme_id, sys_permission_id, group_id) values ';
        for ($i = 0; $i < count($groupArr); $i++)
            $query .= '(' . $permissionSchemeId . ', ' . $permissionId . ', ' . $groupArr[$i] . '),';

        $query = substr($query, 0, strlen($query) - 1);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function addRoleDataToPermissionId($permissionSchemeId, $permissionId, $roleArr, $currentDate) {
        $query = 'insert into permission_scheme_data(permission_scheme_id, sys_permission_id, permission_role_id, date_created) values ';
        for ($i = 0; $i < count($roleArr); $i++)
            $query .= '(' . $permissionSchemeId . ', ' . $permissionId . ', ' . $roleArr[$i] . ", '" . $currentDate . "'),";

        $query = substr($query, 0, strlen($query) - 1);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function deleteDataById($permissionSchemeDataId) {
        $query = "delete from permission_scheme_data where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $permissionSchemeDataId);
        $stmt->execute();
    }

    public function deleteDataByPermissionSchemeId($permissionSchemeId) {
        $query = "delete from permission_scheme_data where permission_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $permissionSchemeId);
        $stmt->execute();
    }

    public function addDefaultPermissions($permissionSchemeId, $roleAdministratorsId, $roleDevelopersId, $roleUsersId, $currentDate) {

        $permissionSchemeRepository = UbirimiContainer::get()['repository']->get(PermissionScheme::class);

        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_ADMINISTER_PROJECTS, array($roleAdministratorsId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_BROWSE_PROJECTS, array($roleUsersId), $currentDate);

        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_CREATE_ISSUE, array($roleUsersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_EDIT_ISSUE, array($roleDevelopersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_ASSIGN_ISSUE, array($roleDevelopersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_ASSIGNABLE_USER, array($roleDevelopersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_RESOLVE_ISSUE, array($roleDevelopersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_CLOSE_ISSUE, array($roleDevelopersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_MODIFY_REPORTER, array($roleAdministratorsId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_DELETE_ISSUE, array($roleAdministratorsId), $currentDate);

        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_ADD_COMMENTS, array($roleUsersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_EDIT_ALL_COMMENTS, array($roleDevelopersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_DELETE_ALL_COMMENTS, array($roleAdministratorsId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_EDIT_OWN_COMMENTS, array($roleUsersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_DELETE_OWN_COMMENTS, array($roleUsersId), $currentDate);

        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_CREATE_ATTACHMENTS, array($roleUsersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_DELETE_ALL_ATTACHMENTS, array($roleAdministratorsId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_DELETE_OWN_ATTACHMENTS, array($roleUsersId), $currentDate);

        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_WORK_ON_ISSUE, array($roleDevelopersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_EDIT_OWN_WORKLOGS, array($roleUsersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_EDIT_ALL_WORKLOGS, array($roleDevelopersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_DELETE_OWN_WORKLOGS, array($roleUsersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_DELETE_ALL_WORKLOGS, array($roleAdministratorsId), $currentDate);

        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_LINK_ISSUE, array($roleDevelopersId), $currentDate);

        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_VIEW_VOTERS_AND_WATCHERS, array($roleDevelopersId), $currentDate);
        $permissionSchemeRepository->addRoleDataToPermissionId($permissionSchemeId, Permission::PERM_MANAGE_WATCHERS, array($roleAdministratorsId), $currentDate);
    }

    public function deleteById($permissionSchemeId) {
        $query = "delete from permission_scheme where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $permissionSchemeId);
        $stmt->execute();
    }

    public function deleteByClientId($clientId) {
        $permissionSchemes = UbirimiContainer::get()['repository']->get(PermissionScheme::class)->getByClientId($clientId);
        while ($permissionSchemes && $permissionScheme = $permissionSchemes->fetch_array(MYSQLI_ASSOC)) {
            UbirimiContainer::get()['repository']->get(PermissionScheme::class)->deleteDataByPermissionSchemeId($permissionScheme['id']);
            UbirimiContainer::get()['repository']->get(PermissionScheme::class)->deleteById($permissionScheme['id']);
        }
    }

    public function addDataRaw($permissionSchemeId, $permissionId, $permissionRoleId, $groupId, $userId, $currentAssignee, $reporter, $projectLead, $currentDate) {
        $query = "INSERT INTO permission_scheme_data(permission_scheme_id, sys_permission_id, permission_role_id, group_id, user_id, current_assignee, reporter, " .
            "project_lead, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiiiiiiis", $permissionSchemeId, $permissionId, $permissionRoleId, $groupId, $userId, $currentAssignee, $reporter, $projectLead, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getByClientIdAndGroupBy($clientId, $groupId) {
        $query = "select permission_scheme.* " .
            "from permission_scheme " .
            "left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id " .
            "where permission_scheme.client_id = ? and permission_scheme_data.group_id = ? " .
            "group by permission_scheme.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }
}
