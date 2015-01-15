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

namespace Ubirimi\Yongo\Repository\Issue;

use Ubirimi\Container\UbirimiContainer;

class IssueSecurityScheme
{
    const SECURITY_SCHEME_DATA_TYPE_CURRENT_ASSIGNEE = 'current_assignee';
    const SECURITY_SCHEME_DATA_TYPE_REPORTER = 'reporter';
    const SECURITY_SCHEME_DATA_TYPE_PROJECT_LEAD = 'project_lead';
    const SECURITY_SCHEME_DATA_TYPE_USER = 'user';
    const SECURITY_SCHEME_DATA_TYPE_GROUP = 'group';
    const SECURITY_SCHEME_DATA_TYPE_PROJECT_ROLE = 'role';

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
        $query = "INSERT INTO issue_security_scheme(client_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isss", $this->clientId, $this->name, $this->description, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getByClientId($clientId) {
        $query = "select * from issue_security_scheme where client_id = ?";

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
        $query = "select * from issue_security_scheme where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function updateMetaDataById($Id, $name, $description) {
        $query = "update issue_security_scheme set name = ?, description = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssi", $name, $description, $Id);
        $stmt->execute();
    }

    public function deleteById($Id) {
        $levels = UbirimiContainer::get()['repository']->get(IssueSecurityScheme::class)->getLevelsByIssueSecuritySchemeId($Id);
        while ($levels && $level = $levels->fetch_array(MYSQLI_ASSOC)) {
            $query = "delete from issue_security_scheme_level_data where issue_security_scheme_level_id = ?";

            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
            $stmt->bind_param("i", $level['id']);
            $stmt->execute();
        }

        $query = "delete from issue_security_scheme_level where issue_security_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();

        $query = "delete from issue_security_scheme where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
    }

    public function getLevelsByIssueSecuritySchemeId($issueSecuritySchemeId) {
        $query = "select * from issue_security_scheme_level where issue_security_scheme_id = ? order by id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueSecuritySchemeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function addLevel($issueSecuritySchemeId, $name, $description, $currentDate) {
        $query = "INSERT INTO issue_security_scheme_level(issue_security_scheme_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isss", $issueSecuritySchemeId, $name, $description, $currentDate);
        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function getLevelById($levelId) {
        $query = "select * from issue_security_scheme_level where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $levelId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getDataByLevelId($levelId) {
        $query = "select issue_security_scheme_level_data.id, issue_security_scheme_level_data.issue_security_scheme_level_id, " .
                 "issue_security_scheme_level_data.permission_role_id, issue_security_scheme_level_data.group_id, issue_security_scheme_level_data.user_id, " .
                 "issue_security_scheme_level_data.current_assignee, issue_security_scheme_level_data.reporter, issue_security_scheme_level_data.project_lead, issue_security_scheme_level_data.date_created, " .
                 "general_user.first_name, general_user.last_name, general_user.id as user_id, general_group.id as group_id, general_group.name as group_name, permission_role.name as role_name " .
                 "from issue_security_scheme_level_data " .
                 "left join general_user on general_user.id = issue_security_scheme_level_data.user_id " .
                 "left join `general_group` on  `general_group`.id = issue_security_scheme_level_data.group_id " .
                 "left join permission_role on permission_role.id = issue_security_scheme_level_data.permission_role_id " .
                 "where issue_security_scheme_level_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $levelId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function addLevelData($levelId, $levelDataType, $user, $group, $role, $currentDate) {
        switch ($levelDataType) {

            case IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_USER:
                $query = "INSERT INTO issue_security_scheme_level_data(issue_security_scheme_level_id, user_id, date_created) VALUES (?, ?, ?)";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

                $stmt->bind_param("iis", $levelId, $user, $currentDate);
                $stmt->execute();

                return UbirimiContainer::get()['db.connection']->insert_id;

                break;

            case IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_GROUP:
                $query = "INSERT INTO issue_security_scheme_level_data(issue_security_scheme_level_id, group_id, date_created) VALUES (?, ?, ?)";
                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

                $stmt->bind_param("iis", $levelId, $group, $currentDate);
                $stmt->execute();

                return UbirimiContainer::get()['db.connection']->insert_id;

                break;

            case IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_PROJECT_ROLE:
                $query = "INSERT INTO issue_security_scheme_level_data(issue_security_scheme_level_id, permission_role_id, date_created) VALUES (?, ?, ?)";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

                $stmt->bind_param("iis", $levelId, $role, $currentDate);
                $stmt->execute();

                return UbirimiContainer::get()['db.connection']->insert_id;

                break;

            case IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_CURRENT_ASSIGNEE:
            case IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_REPORTER:
            case IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_PROJECT_LEAD:
                $query = "INSERT INTO issue_security_scheme_level_data(issue_security_scheme_level_id, `" . $levelDataType . "`, date_created) VALUES (?, ?, ?)";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $value = 1;
                $stmt->bind_param("iis", $levelId, $value, $currentDate);
                $stmt->execute();

                return UbirimiContainer::get()['db.connection']->insert_id;

                break;
        }
    }

    public function updateLevelById($issueSecuritySchemeLevelId, $name, $description, $date) {
        $query = "update issue_security_scheme_level set name = ?, description = ?, date_updated = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $issueSecuritySchemeLevelId);
        $stmt->execute();
    }

    public function deleteLevelById($issueSecuritySchemeLevelId) {
        $query = "delete from issue_security_scheme_level_data where issue_security_scheme_level_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueSecuritySchemeLevelId);
        $stmt->execute();

        $query = "delete from issue_security_scheme_level where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueSecuritySchemeLevelId);
        $stmt->execute();
    }

    public function deleteLevelDataById($issueSecuritySchemeLevelDataId) {
        $query = "delete from issue_security_scheme_level_data where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueSecuritySchemeLevelDataId);
        $stmt->execute();
    }

    public function makeAllLevelsNotDefault($securitySchemeId) {
        $query = "update issue_security_scheme_level set default_flag = 0 where issue_security_scheme_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $securitySchemeId);
        $stmt->execute();
    }

    public function setLevelDefault($securityLevelId) {
        $query = "update issue_security_scheme_level set default_flag = 1 where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $securityLevelId);
        $stmt->execute();
    }

    public function getDefaultLevel($securitySchemeId) {
        $query = "select * from issue_security_scheme_level where issue_security_scheme_id = ? and default_flag = 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $securitySchemeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getLevelDataById($issueSecuritySchemeLevelDataId) {
        $query = "select * from issue_security_scheme_level_data where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueSecuritySchemeLevelDataId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }
}
