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

namespace Ubirimi\Repository\User;

use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\Container\UbirimiContainer;

class UbirimiUser
{
    public function getPermissionRolesByUserId($userId, $resultType = null, $field = null) {
        $query = 'select distinct permission_role_id from permission_role_data where user_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $finalResult = null;
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultData = array();
                while ($group_result = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($field)
                        $resultData[] = $group_result[$field];
                    else
                        $resultData[] = $group_result;
                }
                $finalResult = $resultData;
            } else $finalResult = $result;
        }

        return $finalResult;
    }

    public function getGroupsByUserId($userId, $resultType = null, $field = null) {
        $query = 'select distinct group_id from general_group_data where user_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $finalResult = null;
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultData = array();
                while ($group_result = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($field)
                        $resultData[] = $group_result[$field];
                    else
                        $resultData[] = $group_result;
                }
                $finalResult = $resultData;
            } else $finalResult = $result;
        }

        return $finalResult;
    }

    public function createAdministratorUser($admin_first_name, $admin_last_name, $admin_username, $password, $admin_email, $clientId, $issuesPerPage, $svnAdministratorFlag, $clientAdministratorFlag, $currentDate) {
        $hash = UbirimiContainer::get()['password']->hash($password);

        $query = "INSERT INTO general_user(first_name, last_name, username, password, email, " .
                                  "client_id, issues_per_page, svn_administrator_flag, client_administrator_flag, date_created) " .
                        "VALUES ('" . $admin_first_name . "', '" . $admin_last_name . "', '" . $admin_username . "', '" . $hash . "', '" . $admin_email .
                                 "', " . $clientId . ", " . $issuesPerPage . ", " . $svnAdministratorFlag . ", " . $clientAdministratorFlag . ", '" . $currentDate . "')";
        UbirimiContainer::get()['db.connection']->query($query);

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function add($clientId, $firstName, $lastName, $email, $username, $password, $issuesPerPage, $customerServiceDeskFlag, $countryId, $currentDate) {
        $query = "INSERT INTO general_user(client_id, country_id, first_name, last_name, email, username, password, issues_per_page, customer_service_desk_flag, date_created) " .
                 "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $hash = UbirimiContainer::get()['password']->hash($password);

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iisssssiis", $clientId, $countryId, $firstName, $lastName, $email, $username, $hash, $issuesPerPage, $customerServiceDeskFlag, $currentDate);
        $stmt->execute();

        return array(UbirimiContainer::get()['db.connection']->insert_id, $password);
    }

    public function deleteById($userId) {
        // delete yongo related entities
        $query = 'delete from issue_comment where user_id = ' . $userId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from issue_attachment where user_id = ' . $userId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from permission_scheme_data where user_id = ' . $userId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from notification_scheme_data where user_id = ' . $userId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'update project set lead_id = NULL where lead_id = ' . $userId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'update project_component set leader_id = NULL where leader_id = ' . $userId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from general_group_data where user_id = ' . $userId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from project_role_data where user_id = ' . $userId;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = 'delete from permission_role_data where default_user_id = ' . $userId;
        UbirimiContainer::get()['db.connection']->query($query);

        // delete calendar related entities
        UbirimiContainer::get()['repository']->get(UbirimiCalendar::class)->deleteByUserId($userId);

        // todo: delete documentador related entities

        $query = 'delete from general_user where id = ' . $userId . ' LIMIT 1';
        UbirimiContainer::get()['db.connection']->query($query);

        // todo: delete svn related entities
    }

    public function getById($Id) {
        $query = "select general_user.id, general_user.client_id, password, first_name, last_name, email, username, general_user.date_created, general_user.avatar_picture, " .
                 "issues_per_page, notify_own_changes_flag, client_administrator_flag, customer_service_desk_flag, " .
                 "sys_country.name as country_name, issues_display_columns " .
            "from general_user " .
            "left join sys_country on sys_country.id = general_user.country_id " .
            "WHERE general_user.id = ? " .
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

    public function getByIds($Ids, $resultType = null) {
        $query = "select general_user.id, general_user.client_id, password, first_name, last_name, email, username, general_user.date_created, general_user.avatar_picture, " .
                 "issues_per_page, notify_own_changes_flag, client_administrator_flag, customer_service_desk_flag, sys_country.name as country_name " .
            "from general_user " .
            "left join sys_country on sys_country.id = general_user.country_id " .
            "WHERE general_user.id IN (" . implode(', ', $Ids) . ")";

        $finalResult = null;
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultData = array();
                while ($user = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultData[] = $user;
                }
                $finalResult = $resultData;
            } else $finalResult = $result;
        } else {
            $finalResult = null;
        }

        return $finalResult;
    }

    public function updateById($userId, $firstName, $lastName, $email, $username, $issuesPerPage = null, $clientAdministratorFlag = 0, $customerServiceDeskFlag, $date) {
        $query = 'update general_user set ' .
                 'first_name = ?, last_name = ?, email = ?, username = ?, client_administrator_flag = ?, customer_service_desk_flag = ?, date_updated = ? ';

        if ($issuesPerPage)
            $query .= ', issues_per_page = ? ';

        $query .= 'WHERE id = ? ' .
                  'LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($issuesPerPage)
            $stmt->bind_param("ssssiisii", $firstName, $lastName, $email, $username, $clientAdministratorFlag, $customerServiceDeskFlag, $date, $issuesPerPage, $userId);
        else
            $stmt->bind_param("ssssiisi", $firstName, $lastName, $email, $username, $clientAdministratorFlag, $customerServiceDeskFlag, $date, $userId);

        $stmt->execute();
    }

    public function checkUserInProjectRoleId($userId, $projectId, $roleId) {
        $query = "SELECT project_role_data.id, project_role_data.user_id " .
            "FROM project_role_data " .
            "WHERE permission_role_id = ? " .
            "AND project_id = ? " .
            "AND user_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $roleId, $projectId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getGroupsForUserIdAndRoleId($userId, $projectId, $roleId, $groupIds) {
        $queryCondition = '';
        if (!empty($groupIds)) {
            $queryCondition = " OR group_id IN (" . implode(', ', $groupIds) . ')';
        }

        $query = "SELECT project_role_data.id, project_role_data.user_id, general_group.id as group_id, general_group.name as group_name " .
            "FROM project_role_data " .
            "left join `general_group` on  `general_group`.id = project_role_data.group_id " .
            "WHERE permission_role_id = ? " .
            "AND project_id = ? " .
            "AND (user_id = ? " . $queryCondition . ') ' .
            "and project_role_data.group_id is not null";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $roleId, $projectId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function checkProjectRole($userId, $projectId, $roleId, $groupIds) {
        $query = "SELECT project_role_data.id, project_role_data.user_id, general_group.id as group_id, general_group.name as group_name " .
                    "FROM project_role_data " .
                    "left join `general_group` on  `general_group`.id = project_role_data.group_id " .
                    "WHERE permission_role_id = ? " .
                        "AND project_id = ? " .
                        "AND (user_id = ? OR group_id IN (" . implode(', ', $groupIds) . ')) ' .
                    "order by project_role_data.user_id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $roleId, $projectId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByClientId($clientId, $helpDeskFlag = 0) {
        $query = "select general_user.*, help_organization.name as organization_name " .
            "from general_user " .
            'left join help_organization_user on help_organization_user.user_id = general_user.id ' .
            'left join help_organization on help_organization.id = help_organization_user.help_organization_id ' .
            "WHERE general_user.client_id = ? " .
            'and customer_service_desk_flag = ' . $helpDeskFlag . ' ' .
            "order by general_user.first_name, general_user.last_name asc";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function hasGlobalPermission($clientId, $userId, $globalPermissionId) {
        $query = 'select general_user.id as user_id, general_user.first_name, general_user.last_name ' .
            'from sys_permission_global_data ' .
            'left join `general_group_data` on `general_group_data`.group_id = sys_permission_global_data.group_id ' .
            'left join general_user on general_user.id = general_group_data.user_id ' .
            'where sys_permission_global_data.client_id = ? and ' .
            'sys_permission_global_data.sys_permission_global_id = ? and ' .
            'general_group_data.user_id = ? and ' .
            'general_user.id is not null ' .

            ' UNION ' .

            'select general_user.id as user_id, general_user.first_name, general_user.last_name ' .
            'from sys_permission_global_data ' .
            'left join general_user on general_user.id = sys_permission_global_data.user_id ' .
            'where sys_permission_global_data.client_id = ? and ' .
            'sys_permission_global_data.sys_permission_global_id = ? and ' .
            'sys_permission_global_data.user_id = ? and ' .
            'general_user.id is not null ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiiiii", $clientId, $globalPermissionId, $userId, $clientId, $globalPermissionId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteGroupsByUserId($userId) {
        $query = 'delete from general_group_data where user_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    public function addGroups($userId, $assigned_user_groups) {
        $query = 'insert into general_group_data(group_id, user_id) values ';

        for ($i = 0; $i < count($assigned_user_groups); $i++)
            $query .= '(' . $assigned_user_groups[$i] . ' ,' . $userId . '), ';

        $query = substr($query, 0, strlen($query) - 2);

        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function updatePassword($userId, $hash) {
        $query = 'update general_user set password = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $hash, $userId);
        $stmt->execute();
    }

    public function getByUsernameAndBaseURL($username, $baseURL) {
        $query = 'SELECT username, general_user.id, email, first_name, last_name, client_id, issues_per_page, password,
                         super_user_flag, client.company_domain, svn_administrator_flag, client_administrator_flag ' .
            'from general_user ' .
            'left join client on client.id = general_user.client_id ' .
            "WHERE username = ? " .
            "and client.base_url = ? " .
            "LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ss", $username, $baseURL);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getByUsernameAndClientDomain($username, $domain) {
        $query = 'SELECT username, general_user.id, email, first_name, last_name, client_id, issues_per_page, password,
                         super_user_flag, client.company_domain, svn_administrator_flag, client_administrator_flag ' .
            'from general_user ' .
            'left join client on client.id = general_user.client_id ' .
            "WHERE username = ? " .
            "and client.company_domain = ? " .
            "LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ss", $username, $domain);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getByUsernameAndClientId($username, $clientId, $resultColumn = null, $userId = null) {
        $query = 'SELECT username, general_user.id, email, first_name, last_name, client_id, issues_per_page, password,
                         super_user_flag, svn_administrator_flag, client_administrator_flag, avatar_picture, issues_display_columns ' .
                 'from general_user ' .
                 "WHERE LOWER(username) = ? " .
                 "and client_id = ? and customer_service_desk_flag = 0 ";

        if ($userId) {
            $query .= 'and general_user.id != ? ';
        }

        $query .= "LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $username = mb_strtolower($username);
        if ($userId) {
            $stmt->bind_param("sii", $username, $clientId, $userId);
        } else {
            $stmt->bind_param("si", $username, $clientId);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            $data = $result->fetch_array(MYSQLI_ASSOC);
            if ($resultColumn) {
                return $data[$resultColumn];
            } else {
                return $data;
            }
        } else
            return null;
    }

    public function getCustomerByEmailAddressAndClientId($username, $clientId) {
        $query = 'select general_user.id, email, first_name, last_name, client_id, password, avatar_picture, issues_display_columns ' .
                 'from general_user ' .
                 "WHERE email = ? and customer_service_desk_flag = 1 and client_id = ? " .
                 "LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("si", $username, $clientId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            $data = $result->fetch_array(MYSQLI_ASSOC);
            return $data;
        } else
            return null;
    }

    public function getByEmailAddress($clientId, $emailAddress) {
        $query = 'SELECT username, id, email, first_name, last_name, client_id, issues_per_page, password,
                    super_user_flag, svn_administrator_flag, client_administrator_flag ' .
                 'from general_user ' .
                 "WHERE client_id = ? and email = ? " .
                 "LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("is", $clientId, mb_strtolower($emailAddress));
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getByUsernameAndAdministrator($username) {
        $query = 'SELECT username, id, email, first_name, last_name, client_id, issues_per_page, password, super_user_flag, svn_administrator_flag ' .
            'from general_user ' .
            "WHERE username = ? and client_administrator_flag = 1 and customer_service_desk_flag = 0 " .
            "LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getByUsernameAndPassword($username, $password)
    {
        $query = 'SELECT username, id, email, first_name, last_name, client_id, issues_per_page, password, super_user_flag, svn_administrator_flag ' .
            'from general_user ' .
            "WHERE username = ? and password = ? " .
            "LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getAll($filters = array()) {
        $query = 'select general_user.id, general_user.first_name, general_user.last_name, general_user.username, general_user.date_created, general_user.email, client_administrator_flag, ' .
                 'client.company_name as client_company_name ' .
                 'from general_user ' .
                 'left join client on client.id = general_user.client_id ' .
                 'where 1 = 1';

        if (!empty($filters['today'])) {
            $query .= " and DATE(user.date_created) = DATE(NOW())";
        }

        if (empty($filters['sort_by'])) {
            $query .= ' order by client.id';
        } else {
            $query .= " order by " . $filters['sort_by'] . ' ' . $filters['sort_order'];
        }

        if (isset($filters['limit'])) {
            $query .= ' limit ' . $filters['limit'];
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function getYongoSettings($userId) {
        $query = 'select issues_per_page, notify_own_changes_flag, country_id, username, email ' .
            'from general_user ' .
            'where id = ? ' .
            'limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function updatePreferences($userId, $parameters) {
        $query = 'update general_user set ';

        $values = array();
        $values_ref = array();
        $valuesType = '';
        for ($i = 0; $i < count($parameters); $i++) {
            $query .= $parameters[$i]['field'] .= ' = ?, ';
            $values[] = $parameters[$i]['value'];
            $valuesType .= $parameters[$i]['type'];
        }
        $query = substr($query, 0, strlen($query) - 2) . ' ' ;

        $query .= 'WHERE id = ? ' .
            'LIMIT 1';
        $values[] = $userId;
        $valuesType .= 'i';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        foreach ($values as $key => $value)
            $values_ref[$key] = &$values[$key];

        if ($valuesType != '')
            call_user_func_array(array($stmt, "bind_param"), array_merge(array($valuesType), $values_ref));
        $stmt->execute();

        $result = $stmt->get_result();
    }

    public function getNotSVNAdministrators($clientId) {
        $query = 'select general_user.* from general_user WHERE client_id = ? and svn_administrator_flag = 0 and customer_service_desk_flag = 0';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByEmailAddressAndBaseURL($address, $baseURL) {
        $query = 'SELECT username, general_user.id, email, first_name, last_name, client_id, issues_per_page, password, ' .
                  'super_user_flag, client.company_domain, svn_administrator_flag ' .
            'from general_user ' .
            'left join client on client.id = general_user.client_id ' .
            "WHERE email = ? " .
            "and client.base_url = ? " .
            "LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ss", $address, $baseURL);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getCustomerByEmailAddressAndBaseURL($address, $baseURL) {
        $query = 'select general_user.id, email, first_name, last_name, client_id, password, ' .
                  'client.company_domain ' .
            'from general_user ' .
            'left join client on client.id = general_user.client_id ' .
            "WHERE email = ? " .
                "and general_user.customer_service_desk_flag = 1 " .
                "and client.base_url = ? " .
            "LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ss", $address, $baseURL);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getIssueSecurityLevelsBySecuritySchemeId($issue, $loggedInUserId) {
        $securityLevelId = $issue['security_level'];
        $projectId = $issue['issue_project_id'];
        $issueId = $issue['id'];

        // 1. user in security scheme level data
        $query =
            'SELECT issue_security_scheme_level_data.id ' .
                'from issue_security_scheme_level_data ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = ? and ' .
                'issue_security_scheme_level_data.user_id = ? ' .
                // 2. group - user in security scheme level data
                'UNION DISTINCT ' .
                'SELECT issue_security_scheme_level_data.id ' .
                'from issue_security_scheme_level_data ' .
                'left join `general_group` on general_group.id = issue_security_scheme_level_data.group_id ' .
                'left join `general_group_data` on general_group_data.group_id = `general_group`.id ' .
                'left join general_user on general_user.id = general_group_data.user_id ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = ? and ' .
                'issue_security_scheme_level_data.user_id = ? ' .
                // 3. permission role in security scheme level data - user
                'UNION DISTINCT ' .
                'SELECT issue_security_scheme_level_data.id ' .
                'from issue_security_scheme_level_data ' .
                'left join project_role_data on project_role_data.permission_role_id = issue_security_scheme_level_data.permission_role_id ' .
                'left join general_user on general_user.id = project_role_data.user_id ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = ? and ' .
                'issue_security_scheme_level_data.user_id = ? ' .
                // 4. permission role in security scheme level data - group
                'UNION DISTINCT ' .
                'SELECT issue_security_scheme_level_data.id ' .
                'from issue_security_scheme_level_data ' .
                'left join project_role_data on project_role_data.permission_role_id = issue_security_scheme_level_data.permission_role_id ' .
                'left join `general_group` on general_group.id = project_role_data.group_id ' .
                'left join `general_group_data` on general_group_data.group_id = `general_group`.id ' .
                'left join general_user on general_user.id = general_group_data.user_id ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = ? and ' .
                'issue_security_scheme_level_data.user_id = ? ' .
                // 5. current_assignee in security scheme level data
                'UNION DISTINCT ' .
                'SELECT issue_security_scheme_level_data.id ' .
                'from issue_security_scheme_level_data ' .
                'left join yongo_issue on yongo_issue.id = ? ' .
                'left join general_user on general_user.id = yongo_issue.user_assigned_id ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = ? and ' .
                'issue_security_scheme_level_data.current_assignee is not null and ' .
                'yongo_issue.user_assigned_id is not null and ' .
                'general_user.id = ? ' .
                // 6. reporter in security scheme level data
                'UNION DISTINCT ' .
                'SELECT issue_security_scheme_level_data.id ' .
                'from issue_security_scheme_level_data ' .
                'left join yongo_issue on yongo_issue.id = ? ' .
                'left join general_user on general_user.id = yongo_issue.user_reported_id ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = ? and ' .
                'issue_security_scheme_level_data.reporter is not null and ' .
                'yongo_issue.user_reported_id is not null and ' .
                'general_user.id = ? ' .
                // 7. project_lead in security scheme level data

                'UNION DISTINCT ' .
                'SELECT issue_security_scheme_level_data.id ' .
                'from issue_security_scheme_level_data ' .
                'left join project on project.id = ? ' .
                'left join general_user on general_user.id = project.lead_id ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = ? and ' .
                'issue_security_scheme_level_data.project_lead is not null and ' .
                'project.lead_id is not null and ' .
                'general_user.id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiiiiiiiiiiiiiiii", $securityLevelId, $loggedInUserId, $securityLevelId, $loggedInUserId, $securityLevelId, $loggedInUserId, $securityLevelId, $loggedInUserId, $issueId, $securityLevelId, $loggedInUserId, $issueId, $securityLevelId, $loggedInUserId, $projectId, $securityLevelId, $loggedInUserId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return true;
        else
            return false;
    }

    public function getDocumentadorActivityStream($userId) {
        // created pages
        $query = 'select documentator_entity.name, documentator_entity.id, \'created\' as action, documentator_entity.date_created as date ' .
                 'from documentator_entity ' .
                 'where documentator_entity.user_created_id = ? ';

        // created spaces
        $query .= ' UNION ' .
            'select documentator_space.name, documentator_space.id, \'created_space\' as action, documentator_space.date_created as date ' .
            'from documentator_space ' .
            'where documentator_space.user_created_id = ? ';

        // edited pages
        $query .= ' UNION ' .
                  'select documentator_entity.name, documentator_entity.id, \'edited\' as action, documentator_entity_revision.date_created as date ' .
                  'from documentator_entity_revision ' .
                  'left join documentator_entity on documentator_entity.id = documentator_entity_revision.entity_id ' .
                  'where documentator_entity_revision.user_id = ? ';

        // comments
        $query .= ' UNION ' .
                  'select documentator_entity.name, documentator_entity.id, \'comment\' as action, documentator_entity_comment.date_created as date ' .
                  'from documentator_entity_comment ' .
                  'left join documentator_entity on documentator_entity.id = documentator_entity_comment.documentator_entity_id ' .
                  'where documentator_entity_comment.user_id = ? ';

        $query .= 'order by date desc';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiii", $userId, $userId, $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByEmailAddressAndIsClientAdministrator($emailAddress) {
        $query = 'select email, id from general_user where client_administrator_flag = 1 and LOWER(email) = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("s", $emailAddress);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else {
            return null;
        }
    }

    public function getByUsernameAndIsClientAdministrator($username) {
        $query = 'select username, id from general_user where client_administrator_flag = 1 and LOWER(username) = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->num_rows;
        else {
            return null;
        }
    }

    public function getUserByClientIdAndEmailAddress($clientId, $email) {
        $query = 'select email, id from general_user where client_id = ? and LOWER(email) = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $clientId, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->num_rows;
        else {
            return null;
        }
    }

    public function updateAvatar($avatar, $userId) {
        $query = 'update general_user set avatar_picture = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $avatar, $userId);
        $stmt->execute();
    }

    public function getUserAvatarPicture($user, $size = null) {
        if (null !==  $user['avatar_picture'] && !empty($user['avatar_picture'])) {
            $pictureData = pathinfo($user['avatar_picture']);
            $fileName = $pictureData['filename'];
            $extension = $pictureData['extension'];

            if ($size == 'small') {
                return '/assets' . UbirimiContainer::get()['asset.user_avatar'] . $user['id'] . '/' . $fileName . '_33.' . $extension;
            } else if ($size == 'big') {
                return '/assets' . UbirimiContainer::get()['asset.user_avatar'] . $user['id'] . '/' . $fileName . '_150.' . $extension;
            }
        }

        return '/img/small_user.png';
    }

    public function updateDisplayColumns($userId, $data) {
        $query = 'update general_user set issues_display_columns = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $data, $userId);
        $stmt->execute();
    }

    public function updateLoginTime($userId, $datetime)
    {
        $query = "update general_user set last_login = ? WHERE id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("si", $datetime, $userId);
        $stmt->execute();
    }

    public function getByClientIdAndFullName($clientId, $fullName) {
        $query = 'select * from general_user where client_id = ? and CONCAT(first_name, " ", last_name) = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $clientId, $fullName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else {
            return null;
        }
    }
}