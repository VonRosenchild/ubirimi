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

namespace Ubirimi\Yongo\Repository\Project;

use Ubirimi\Agile\Repository\Board\Board;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\HelpDesk\Repository\Queue\Queue;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\HelpDesk\Repository\Sla\SlaCalendar;
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\CustomField;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
use Ubirimi\Yongo\Repository\Screen\Screen;
use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

class YongoProject
{
    public function getLast5ByClientId($clientId) {
        $query = "select *  " .
            'from project ' .
            'where client_id = ? ' .
            'order by id desc ' .
            'limit 5';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getCount($clientId) {
        $query = 'SELECT count(id) as total FROM project WHERE client_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = $result->fetch_array(MYSQLI_ASSOC);

        return $data['total'];
    }

    public function getWorkflowScheme($projectId) {
        $query = "select workflow_scheme.*  " .
            'from project ' .
            'left join workflow_scheme on workflow_scheme.id = project.workflow_scheme_id ' .
            'where project.id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getByClientId($clientId) {
        $query = 'SELECT * ' .
            'FROM project ' .
            'WHERE project.client_id = ? ' .
            'order by project.name';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByClientIdAndIds($clientId, $projectIds) {
        $query = 'SELECT * ' .
            'FROM project ' .
            'WHERE project.client_id = ? and id IN (' . implode(", ", $projectIds) . ")";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByIssueTypeFieldConfigurationScheme($clientId, $issueTypeFieldConfigurationId) {
        $query = 'SELECT * ' .
            'FROM project ' .
            'WHERE project.issue_type_field_configuration_id = ? and client_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $issueTypeFieldConfigurationId, $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByIssueTypeScreenSchemeId($clientId, $issueTypeScreenSchemeId) {
        $query = 'SELECT * ' .
            'FROM project ' .
            'WHERE project.issue_type_screen_scheme_id = ? and client_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $issueTypeScreenSchemeId, $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getWorkflowUsedForType($projectId, $issueTypeId) {
        $query = "select workflow.id, workflow.name  " .
            'from project ' .
            'left join workflow_scheme on workflow_scheme.id = project.workflow_scheme_id ' .
            'left join workflow_scheme_data on workflow_scheme_data.workflow_scheme_id = workflow_scheme.id ' .
            'left join workflow on workflow.id = workflow_scheme_data.workflow_id ' .
            'left join issue_type_scheme on issue_type_scheme.id = workflow.issue_type_scheme_id ' .
            'left join issue_type_scheme_data on issue_type_scheme_data.issue_type_scheme_id = issue_type_scheme.id ' .
            'left join issue_type on issue_type.id = issue_type_scheme_data.issue_type_id ' .
            'where project.id = ? ' .
            'and issue_type.id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $projectId, $issueTypeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getAllIssueTypesForProjects($projectIdOrArray) {
        $query = 'SELECT issue_type.id, issue_type.name, issue_type.description ' .
            'FROM project ' .
            'left join issue_type_scheme on issue_type_scheme.id = project.issue_type_scheme_id ' .
            'left join issue_type_scheme_data on issue_type_scheme_data.issue_type_scheme_id = issue_type_scheme.id ' .
            'left join issue_type on issue_type.id = issue_type_scheme_data.issue_type_id ' .
            'where project.id IN (' . implode(', ', $projectIdOrArray) . ')';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByIssueTypeScheme($schemeId) {
        $query = 'SELECT * ' .
            'FROM project ' .
            'WHERE project.issue_type_scheme_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $schemeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByPermissionScheme($schemeId) {
        $query = 'SELECT * ' .
            'FROM project ' .
            'WHERE project.permission_scheme_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $schemeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByIssueSecurityScheme($schemeId) {
        $query = 'SELECT * ' .
            'FROM project ' .
            'WHERE project.issue_security_scheme_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $schemeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByNotificationScheme($schemeId) {
        $query = 'SELECT * ' .
            'FROM project ' .
            'WHERE project.notification_scheme_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $schemeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByIds($projectIds) {
        $query = 'SELECT project.id, project.client_id, code, name ' .
            'FROM project ' .
            'WHERE project.id IN (' . implode(', ', $projectIds) . ')';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public function getById($projectId) {
        $query = 'SELECT project.id, project.client_id, permission_scheme_id, lead_id, code, name,' .
                    'issue_type_screen_scheme_id, issue_type_field_configuration_id, workflow_scheme_id, notification_scheme_id, ' .
                    'description, user.first_name, user.last_name, issue_type_scheme_id, issue_security_scheme_id, project_category_id, ' .
                    'help_desk_enabled_flag ' .
                 'FROM project ' .
                 'LEFT JOIN user ON user.id = project.lead_id ' .
                 'WHERE project.id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function updateLeader($projectId, $leaderId) {
        $query = 'update project set lead_id = ? where id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $leaderId, $projectId);
        $stmt->execute();
    }

    public function getByCode($code, $projectId, $clientId) {
        $query = 'select id, name, code from project where client_id = ? and LOWER(code) = LOWER(?) ';
        if ($projectId) $query .= 'and id != ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($projectId)
            $stmt->bind_param("isi", $clientId, $code, $projectId);
        else
            $stmt->bind_param("is", $clientId, $code);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function getByName($name, $projectId, $clientId) {
        $query = 'select id, name, code from project where client_id = ? and LOWER(name) = LOWER(?) ';
        if ($projectId) $query .= 'and id != ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($projectId)
            $stmt->bind_param("isi", $clientId, $name, $projectId);
        else
            $stmt->bind_param("is", $clientId, $name);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function addComponent($projectId, $name, $description, $leaderId, $parentComponentId, $date) {
        if (!$leaderId) $leaderId = 'NULL';
        if (!$parentComponentId) $parentComponentId = 'NULL';

        $query = "INSERT INTO project_component(project_id, leader_id, parent_id, name, description, date_created) " .
                 "VALUES (" . $projectId . ", " . $leaderId . ", " . $parentComponentId . ", '" . $name . "','" . $description . "', '" . $date . "')";

        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function getSubComponents($parentComponentId) {
        $query = 'SELECT project_component.id, project_component.project_id, project_component.name, project_component.description, user.id as user_id, user.first_name, user.last_name ' .
            'FROM project_component ' .
            'left join user on user.id = project_component.leader_id ' .
            'where parent_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $parentComponentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            return $result;
        } else
            return null;
    }

    public function renderTreeComponentsInViewIssue($component, $htmlComponent) {
        $htmlComponent = LinkHelper::getYongoProjectComponentLink($component['project_component_id'], $component['name']) . ' / ' . $htmlComponent;
        $parentComponent = UbirimiContainer::get()['repository']->get(YongoProject::class)->getParentComponent($component['parent_id'], 'array');
        if ($parentComponent) {
            UbirimiContainer::get()['repository']->get(YongoProject::class)->renderTreeComponentsInViewIssue($parentComponent, $htmlComponent);
        } else {
            if (substr($htmlComponent, strlen($htmlComponent) - 3) == ' / ') {
                $htmlComponent = substr($htmlComponent, 0, strlen($htmlComponent) - 3);
            }
            echo $htmlComponent . '<br />';
        }
    }

    public function renderTreeComponentsInCombobox($components, $identationIndex, $arrSelectedIssueComponents = null, &$printedComponents) {
        while ($components && $component = $components->fetch_array(MYSQLI_ASSOC)) {

            if (!in_array($component['id'], $printedComponents)) {
                $textSelected = '';
                if ($arrSelectedIssueComponents) {
                    if (in_array($component['id'], $arrSelectedIssueComponents))
                        $textSelected = 'selected="selected"';
                }
                echo '<option ' . $textSelected . ' value="' . $component['id'] . '">';
                    for ($i = 0; $i < $identationIndex; $i++) {
                        echo '&nbsp;&nbsp;&nbsp;';
                    }
                    echo $component['name'];
                echo '</option>';

                $printedComponents[] = $component['id'];
                $subComponents = UbirimiContainer::get()['repository']->get(YongoProject::class)->getSubComponents($component['id']);
                $identationIndex++;

                if ($subComponents) {
                    UbirimiContainer::get()['repository']->get(YongoProject::class)->renderTreeComponentsInCombobox($subComponents, $identationIndex, $arrSelectedIssueComponents, $printedComponents);
                }
                $identationIndex--;
            }
        }
    }

    public function renderTreeComponents($components, $identationIndex) { ?>
        <?php while ($components && $component = $components->fetch_array(MYSQLI_ASSOC)): ?>
            <tr id="table_row_<?php echo $component['id'] ?>">
                <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $component['id'] ?>"/></td>
                <td>
                    <?php for ($i = 0; $i < $identationIndex; $i++): ?>
                        &nbsp;&nbsp;&nbsp;
                    <?php endfor ?>
                    <?php echo $component['name']; ?>
                </td>
                <td><?php echo $component['description']; ?></td>
                <td>
                    <?php if ($component['user_id']): ?>
                    <?php echo LinkHelper::getUserProfileLink($component['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $component['first_name'], $component['last_name']); ?></td>
                <?php else: ?>
                    <span>No one</span>
                <?php
                    endif ?>
            </tr>
            <?php
                $subComponents = UbirimiContainer::get()['repository']->get(YongoProject::class)->getSubComponents($component['id']);
                $identationIndex++;
                if ($subComponents) {
                    UbirimiContainer::get()['repository']->get(YongoProject::class)->renderTreeComponents($subComponents, $identationIndex);
                }
                $identationIndex--;
            ?>
        <?php endwhile ?> <?php
    }

    public function getComponents($projectIdOrArray = null, $resultType = null, $onlyParents = null) {
        $query = 'SELECT project_component.id, project_component.project_id, project_component.name, project_component.description, user.id as user_id, user.first_name, user.last_name, ' .
                    'pc_parent.name as parent_name ' .
                 'FROM project_component ' .
                 'left join project_component as pc_parent on pc_parent.id = project_component.parent_id ' .
                 'left join user on user.id = project_component.leader_id ' .
                 'where 1 = 1 ';

        if (isset($projectIdOrArray)) {
            if (is_array($projectIdOrArray))
                $query .= ' AND project_component.project_id IN ( ' . implode(', ', $projectIdOrArray) . ')';
            else
                $query .= ' AND project_component.project_id = ' . $projectIdOrArray;
        }

        if (isset($onlyParents)) {
            $query .= ' AND project_component.parent_id IS NULL';
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultArray[] = $data;
                }
                return $resultArray;
            } else {
                return $result;
            }
        } else
            return null;
    }

    public function getVersions($projectIdOrArray = null) {
        $query = 'SELECT id, project_id, name, description ' .
                 'FROM project_version ';

        if (isset($projectIdOrArray)) {
            if (is_array($projectIdOrArray))
                $query .= 'WHERE project_id IN ( ' . implode(', ', $projectIdOrArray) . ')';
            else
                $query .= 'WHERE project_id = ' . $projectIdOrArray;
        }

        $query .= ' order by name';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getStatsUnresolvedByComponent($projectId) {
        $projectComponents = UbirimiContainer::get()['repository']->get(YongoProject::class)->getComponents($projectId);
        $stats = array();
        if ($projectComponents) {
            while ($component = $projectComponents->fetch_array(MYSQLI_ASSOC)) {
                $q = 'select count(yongo_issue.id) as total ' .
                        'from yongo_issue ' .
                        'left join issue_component on yongo_issue.id = issue_component.issue_id ' .
                        'where project_id = ? ' .
                        'and issue_component.project_component_id = ? ' .
                        'and yongo_issue.resolution_id is null';

                $stmt = UbirimiContainer::get()['db.connection']->prepare($q);

                $stmt->bind_param("ii", $projectId, $component['id']);

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows) {
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $stats[] = array($component['name'], $row['total'], $component['id']);
                }
                else
                    $stats[] = array($component['name'], 0, $component['id']);
            }

            $q = 'select count(yongo_issue.id) as total ' .
                    'from yongo_issue ' .
                    'left join issue_component on yongo_issue.id = issue_component.issue_id ' .
                    'where project_id = ? ' .
                    'and issue_component.project_component_id IS NULL ' .
                    'and yongo_issue.resolution_id is null';

            $stmt = UbirimiContainer::get()['db.connection']->prepare($q);

            $stmt->bind_param("i", $projectId);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $stats[] = array("No component", $row['total']);
            }
        }

        return $stats;
    }

    public function add(
        $clientId,
        $issueTypeSchemeId,
        $issueTypeScreenSchemeId,
        $issueTypeFieldConfigurationSchemeId,
        $workflowSchemeId,
        $permissionSchemeId,
        $notificationSchemeId,
        $leadId,
        $name,
        $code,
        $description,
        $projectCategoryId,
        $forHelpDesk,
        $currentDate
    ) {
        $query = "INSERT INTO project(client_id, lead_id, issue_type_scheme_id, issue_type_screen_scheme_id, issue_type_field_configuration_id, " .
                    "workflow_scheme_id, permission_scheme_id, notification_scheme_id, name, code, description, project_category_id, help_desk_enabled_flag, date_created) VALUES " .
                 "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("iiiiiiiisssiis",
            $clientId,
            $leadId,
            $issueTypeSchemeId,
            $issueTypeScreenSchemeId,
            $issueTypeFieldConfigurationSchemeId,
            $workflowSchemeId,
            $permissionSchemeId,
            $notificationSchemeId,
            $name,
            $code,
            $description,
            $projectCategoryId,
            $forHelpDesk,
            $currentDate
        );

        $stmt->execute();

        return UbirimiContainer::get()['db.connection']->insert_id;
    }

    public function updateById(
        $projectId,
        $leaderId,
        $name,
        $code,
        $description,
        $issueTypeSchemeId,
        $workflowSchemeId,
        $projectCategoryId,
        $date
    ) {
        $query = "UPDATE project " .
                 "SET lead_id = ?, " .
                     "name = ?, " .
                     "code = ?, " .
                     "description = ?, " .
                     "issue_type_scheme_id = ?, " .
                     "workflow_scheme_id = ?, " .
                     "project_category_id = ?, " .
                     "date_updated = ? " .
                 "WHERE id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("isssiiisi",
            $leaderId,
            $name,
            $code,
            $description,
            $issueTypeSchemeId,
            $workflowSchemeId,
            $projectCategoryId,
            $date,
            $projectId
        );

        $stmt->execute();
    }

    public function deleteById($Id) {
        $query = "SET FOREIGN_KEY_CHECKS = 0;";
        UbirimiContainer::get()['db.connection']->query($query);

        UbirimiContainer::get()['repository']->get(YongoProject::class)->deleteIssuesByProjectId($Id);
        UbirimiContainer::get()['repository']->get(ProjectComponent::class)->deleteByProjectId($Id);
        UbirimiContainer::get()['repository']->get(ProjectVersion::class)->deleteByProjectId($Id);
        UbirimiContainer::get()['repository']->get(CustomField::class)->deleteDataByProjectId($Id);
        UbirimiContainer::get()['repository']->get(Board::class)->deleteByProjectId($Id);

        $query = "DELETE IGNORE FROM project_role_data WHERE project_id = " . $Id;
        UbirimiContainer::get()['db.connection']->query($query);

        // delete the help desk related information
        UbirimiContainer::get()['repository']->get(YongoProject::class)->removeHelpdeskData($Id);

        $query = "DELETE IGNORE FROM help_filter WHERE project_id = " . $Id;
        UbirimiContainer::get()['db.connection']->query($query);

        $query = "DELETE IGNORE FROM project WHERE id = " . $Id . ' LIMIT 1';
        UbirimiContainer::get()['db.connection']->query($query);

        $query = "SET FOREIGN_KEY_CHECKS = 1;";
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function getIssueTypes($projectId, $includeSubTasks, $resultType = null, $resultColumn = null) {
        $query = 'SELECT issue_type.id, issue_type.name, issue_type.description ' .
            'FROM project ' .
            'left join issue_type_scheme on issue_type_scheme.id = project.issue_type_scheme_id ' .
            'left join issue_type_scheme_data on issue_type_scheme_data.issue_type_scheme_id = issue_type_scheme.id ' .
            'left join issue_type on issue_type.id = issue_type_scheme_data.issue_type_id ' .
            'where project.id = ?';

        if ($includeSubTasks == 0) {
            $query .= ' and issue_type.sub_task_flag = 0';
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($resultColumn) {
                        $resultArray[] = $data[$resultColumn];
                    } else {
                        $resultArray[] = $data;
                    }
                }
                return $resultArray;
            } else {
                return $result;
            }
        } else {
            return null;
        }
    }

    public function getSubTasksIssueTypes($projectId, $resultType = null, $resultColumn = null) {
        $query = 'SELECT issue_type.id, issue_type.name, issue_type.description ' .
            'FROM project ' .
            'left join issue_type_scheme on issue_type_scheme.id = project.issue_type_scheme_id ' .
            'left join issue_type_scheme_data on issue_type_scheme_data.issue_type_scheme_id = issue_type_scheme.id ' .
            'left join issue_type on issue_type.id = issue_type_scheme_data.issue_type_id ' .
            'where project.id = ? and issue_type.sub_task_flag = 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($resultColumn) {
                        $resultArray[] = $data[$resultColumn];
                    } else {
                        $resultArray[] = $data;
                    }
                }
                return $resultArray;
            } else {
                return $result;
            }
        } else
            return null;
    }

    public function addDefaultUsers($clientId, $projectId, $currentDate) {
        $query = 'SELECT permission_role_data.default_user_id, permission_role_data.permission_role_id ' .
            'FROM permission_role_data ' .
            'left join permission_role on permission_role.id = permission_role_data.permission_role_id ' .
            'where permission_role.client_id = ? ' .
            'and permission_role_data.default_user_id is not null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                $query_add = "INSERT INTO project_role_data(project_id, permission_role_id, user_id, date_created) VALUES " .
                    "(?, ?, ?, ?)";

                $stmt2 = UbirimiContainer::get()['db.connection']->prepare($query_add);
                $stmt2->bind_param("iiis", $projectId, $data['permission_role_id'], $data['default_user_id'], $currentDate);
                $stmt2->execute();
            }
        }
    }

    public function addDefaultGroups($clientId, $projectId, $currentDate) {
        $query = 'SELECT permission_role_data.default_group_id, permission_role_data.permission_role_id ' .
            'FROM permission_role_data ' .
            'left join permission_role on permission_role.id = permission_role_data.permission_role_id ' .
            'where permission_role.client_id = ? ' .
            'and permission_role_data.default_group_id is not null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $clientId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                $query_add = "INSERT INTO project_role_data(project_id, permission_role_id, group_id, date_created) VALUES " .
                    "(?, ?, ?, ?)";

                $stmt2 = UbirimiContainer::get()['db.connection']->prepare($query_add);
                $stmt2->bind_param("iiis", $projectId, $data['permission_role_id'], $data['default_group_id'], $currentDate);
                $stmt2->execute();
            }
        }
    }

    public function getUsersInRole($projectId, $roleId) {
        $query = 'SELECT user.id as user_id, user.first_name, user.last_name ' .
            'FROM project_role_data ' .
            'left join user on user.id = project_role_data.user_id ' .
            'where project_role_data.project_id = ? and ' .
                'project_role_data.permission_role_id = ? and ' .
                'project_role_data.user_id is not null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $projectId, $roleId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getAllUsersInRole($projectId, $roleId) {
        $query = 'SELECT user.id as user_id, user.first_name, user.last_name ' .
            'FROM project_role_data ' .
            'left join user on user.id = project_role_data.user_id ' .
            'where project_role_data.project_id = ? and ' .
            'project_role_data.permission_role_id = ? and ' .
            'project_role_data.user_id is not null ' .

            'UNION DISTINCT ' .

                'SELECT user.id as user_id, user.first_name, user.last_name ' .
            'FROM project_role_data ' .
            'left join `group` on group.id = project_role_data.group_id ' .
            'left join `group_data` on group_data.group_id = `group`.id ' .
            'left join user on user.id = group_data.user_id ' .
            'where project_role_data.project_id = ? and ' .
            'project_role_data.permission_role_id = ? and ' .
            'project_role_data.group_id is not null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiii", $projectId, $roleId, $projectId, $roleId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getGroupsInRole($projectId, $roleId) {
        $query = 'SELECT group.id as group_id, group.name as group_name ' .
            'FROM project_role_data ' .
            'left join `group` on group.id = project_role_data.group_id ' .
            'where project_role_data.project_id = ? and ' .
                'project_role_data.permission_role_id = ? and ' .
                'project_role_data.group_id is not null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $projectId, $roleId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function deleteUsersByPermissionRole($projectId, $roleId) {
        $query = "delete from project_role_data where project_id = ? and permission_role_id = ? and user_id is not null ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $projectId, $roleId);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteGroupsByPermissionRole($projectId, $roleId) {
        $query = "delete from project_role_data where project_id = ? and permission_role_id = ? and group_id is not null ";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("ii", $projectId, $roleId);
        $stmt->execute();
        $stmt->close();
    }

    public function addUsersForPermissionRole($projectId, $roleId, $userArray, $currentDate) {
        $query = 'insert into project_role_data(project_id, permission_role_id, user_id, date_created) values ';

        for ($i = 0; $i < count($userArray); $i++)
            $query .= '(' . $projectId . ', ' . $roleId . ' ,' . $userArray[$i] . ",'" . $currentDate . "'), ";

        $query = substr($query, 0, strlen($query) - 2);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function addGroupsForPermissionRole($projectId, $permissionRoleId, $groupArrayIds, $currentDate) {
        $query = 'insert into project_role_data(project_id, permission_role_id, group_id, date_created) values ';

        for ($i = 0; $i < count($groupArrayIds); $i++)
            $query .= '(' . $projectId . ', ' . $permissionRoleId . ' ,' . $groupArrayIds[$i] . ",'" . $currentDate . "'), ";

        $query = substr($query, 0, strlen($query) - 2);
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function getUsersWithPermission($projectIdArray, $permissionId, $resultType = null, $resultColumn = null) {

        if (is_array($projectIdArray))
            $projectsSQL = '(' . implode(',', $projectIdArray) . ')';
        else
            $projectsSQL = '(' . $projectIdArray . ')';

        // 1. user in permission scheme
        $query = '(SELECT user.id as user_id, user.first_name, user.last_name ' .
            'from project ' .
            'left join permission_scheme on permission_scheme.id = project.permission_scheme_id ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
            'left join user on user.id = permission_scheme_data.user_id ' .
            'where project.id IN ' . $projectsSQL . ' and ' .
                'permission_scheme_data.sys_permission_id = ? and ' .
                'permission_scheme_data.user_id is not null and ' .
                'user.id is not null) ' .

            // 2. group in permission scheme

            'UNION DISTINCT ' .
            '(SELECT user.id as user_id, user.first_name, user.last_name ' .
            'from project ' .
            'left join permission_scheme on permission_scheme.id = project.permission_scheme_id ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
            'left join `group` on group.id = permission_scheme_data.group_id ' .
            'left join `group_data` on group_data.group_id = `group`.id ' .
            'left join user on user.id = group_data.user_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
                'permission_scheme_data.group_id is not null and ' .
                'permission_scheme_data.sys_permission_id = ? and ' .
                'user.id is not null) ' .

            // 3. permission role in permission scheme - user

            'UNION DISTINCT ' .
            '(SELECT user.id as user_id, user.first_name, user.last_name ' .
            'from project ' .
            'left join permission_scheme on permission_scheme.id = project.permission_scheme_id ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
            'left join project_role_data on project_role_data.permission_role_id = permission_scheme_data.permission_role_id ' .
            'left join user on user.id = project_role_data.user_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
                'project_role_data.user_id is not null and ' .
                'project_role_data.project_id IN ' . $projectsSQL . ' and ' .
                'permission_scheme_data.sys_permission_id = ? and ' .
                'user.id is not null) ' .

            // 4. permission role in permission scheme - group

            'UNION DISTINCT ' .
            '(SELECT user.id as user_id, user.first_name, user.last_name ' .
            'from project ' .
            'left join permission_scheme on permission_scheme.id = project.permission_scheme_id ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
            'left join project_role_data on project_role_data.permission_role_id = permission_scheme_data.permission_role_id ' .
            'left join `group` on group.id = project_role_data.group_id ' .
            'left join `group_data` on group_data.group_id = `group`.id ' .
            'left join user on user.id = group_data.user_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
                'project_role_data.group_id is not null and ' .
                'project_role_data.project_id IN ' . $projectsSQL . ' and ' .
                'permission_scheme_data.sys_permission_id = ? and ' .
                'user.id is not null)' .
            'order by first_name, last_name';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiii", $permissionId, $permissionId, $permissionId, $permissionId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultArray = array();
                while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                    if ($resultColumn) {
                        $resultArray[] = $data[$resultColumn];
                    } else {
                        $resultArray[] = $data;
                    }
                }
                return $resultArray;
            } else {
                return $result;
            }
        } else {
            return null;
        }
    }

    public function getUsersForNotification($projectIdArray, $eventId, $issue, $loggedInUserId) {
        if (is_array($projectIdArray))
            $projectsSQL = '(' . implode(',', $projectIdArray) . ')';
        else
            $projectsSQL = '(' . $projectIdArray . ')';

            // 1. user in permission scheme
        $query = 'SELECT user.id as user_id, user.first_name, user.last_name, user.email, user.notify_own_changes_flag ' .
            'from project ' .
            'left join notification_scheme on notification_scheme.id = project.notification_scheme_id ' .
            'left join notification_scheme_data on notification_scheme_data.notification_scheme_id = notification_scheme.id ' .
            'left join user on user.id = notification_scheme_data.user_id ' .
            'where project.id IN ' . $projectsSQL . ' and ' .
                'notification_scheme_data.event_id = ? and ' .
                'notification_scheme_data.user_id is not null and ' .
                'user.id is not null ' .

            // 2. group in permission scheme

            'UNION DISTINCT ' .
            'SELECT user.id as user_id, user.first_name, user.last_name, user.email, user.notify_own_changes_flag ' .
            'from project ' .
            'left join notification_scheme on notification_scheme.id = project.notification_scheme_id ' .
            'left join notification_scheme_data on notification_scheme_data.notification_scheme_id = notification_scheme.id ' .
            'left join `group` on group.id = notification_scheme_data.group_id ' .
            'left join `group_data` on group_data.group_id = `group`.id ' .
            'left join user on user.id = group_data.user_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
                'notification_scheme_data.group_id is not null and ' .
                'notification_scheme_data.event_id = ? and ' .
                'user.id is not null ' .

            // 3. permission role in permission scheme - user

            'UNION DISTINCT ' .
            'SELECT user.id as user_id, user.first_name, user.last_name, user.email, user.notify_own_changes_flag ' .
            'from project ' .
            'left join notification_scheme on notification_scheme.id = project.notification_scheme_id ' .
            'left join notification_scheme_data on notification_scheme_data.notification_scheme_id = notification_scheme.id ' .
            'left join project_role_data on project_role_data.permission_role_id = notification_scheme_data.permission_role_id ' .
            'left join user on user.id = project_role_data.user_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
                'project_role_data.user_id is not null and ' .
                'notification_scheme_data.event_id = ? and ' .
                'user.id is not null ' .

            // 4. permission role in permission scheme - group

            'UNION DISTINCT ' .
            'SELECT user.id as user_id, user.first_name, user.last_name, user.email, user.notify_own_changes_flag ' .
            'from project ' .
            'left join notification_scheme on notification_scheme.id = project.notification_scheme_id ' .
            'left join notification_scheme_data on notification_scheme_data.notification_scheme_id = notification_scheme.id ' .
            'left join project_role_data on project_role_data.permission_role_id = notification_scheme_data.permission_role_id ' .
            'left join `group` on group.id = project_role_data.group_id ' .
            'left join `group_data` on group_data.group_id = `group`.id ' .
            'left join user on user.id = group_data.user_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
                'project_role_data.group_id is not null and ' .
                'notification_scheme_data.event_id = ? and ' .
                'user.id is not null ' .

            // 5. current_assignee in permission scheme

            'UNION DISTINCT ' .
            'SELECT user.id as user_id, user.first_name, user.last_name, user.email, user.notify_own_changes_flag ' .
            'from project ' .
            'left join notification_scheme on notification_scheme.id = project.notification_scheme_id ' .
            'left join notification_scheme_data on (notification_scheme_data.notification_scheme_id = notification_scheme.id and ' .
                       'notification_scheme_data.current_assignee is not null) ' .
            'left join yongo_issue on yongo_issue.id = ? ' .
            'left join user on user.id = yongo_issue.user_assigned_id ' .
                'where project.id IN ' . $projectsSQL . ' and ' .
                'notification_scheme_data.event_id = ? and ' .
                'notification_scheme_data.current_assignee is not null and ' .
                'yongo_issue.user_assigned_id is not null and ' .
                'user.id is not null ' .

            // 6. reporter in permission scheme

            'UNION DISTINCT ' .
            'SELECT user.id as user_id, user.first_name, user.last_name, user.email, user.notify_own_changes_flag ' .
            'from project ' .
            'left join notification_scheme on notification_scheme.id = project.notification_scheme_id ' .
            'left join notification_scheme_data on (notification_scheme_data.notification_scheme_id = notification_scheme.id and ' .
                       'notification_scheme_data.reporter is not null) ' .
            'left join yongo_issue on yongo_issue.id = ? ' .
            'left join user on user.id = yongo_issue.user_reported_id ' .
            'where project.id IN ' . $projectsSQL . ' and ' .
                'notification_scheme_data.event_id = ? and ' .
                'notification_scheme_data.reporter is not null and ' .
                'yongo_issue.user_reported_id is not null and ' .
                'user.id is not null ' .

            // 7. current_user in permission scheme

            'UNION DISTINCT ' .
            'SELECT user.id as user_id, user.first_name, user.last_name, user.email, user.notify_own_changes_flag ' .
            'from project ' .
            'left join notification_scheme on notification_scheme.id = project.notification_scheme_id ' .
            'left join notification_scheme_data on (notification_scheme_data.notification_scheme_id = notification_scheme.id and ' .
            'notification_scheme_data.current_user is not null) ' .
            'left join user on user.id = ? ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
                'notification_scheme_data.event_id = ? and ' .
                'notification_scheme_data.current_user is not null and ' .
                'user.id is not null ' .

            // 8. all watchers
            
            'UNION DISTINCT ' .
            'SELECT user.id as user_id, user.first_name, user.last_name, user.email, user.notify_own_changes_flag ' .
            'from project ' .
            'left join notification_scheme on notification_scheme.id = project.notification_scheme_id ' .
            'left join notification_scheme_data on (notification_scheme_data.notification_scheme_id = notification_scheme.id and ' .
            'notification_scheme_data.all_watchers is not null) ' .
            'left join yongo_issue on yongo_issue.id = ? ' .
            'left join yongo_issue_watch on yongo_issue_watch.yongo_issue_id = yongo_issue.id ' .
            'left join user on user.id = yongo_issue_watch.user_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
            'notification_scheme_data.event_id = ? and ' .
            'user.id is not null ' .

            // 9. project_lead in permission scheme

            'UNION DISTINCT ' .
            'SELECT user.id as user_id, user.first_name, user.last_name, user.email, user.notify_own_changes_flag ' .
            'from project ' .
            'left join notification_scheme on notification_scheme.id = project.notification_scheme_id ' .
            'left join notification_scheme_data on (notification_scheme_data.notification_scheme_id = notification_scheme.id and ' .
            'notification_scheme_data.project_lead is not null) ' .
            'left join user on user.id = project.lead_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
                'notification_scheme_data.event_id = ? and ' .
                'notification_scheme_data.project_lead is not null and ' .
                'project.lead_id is not null and ' .
                'user.id is not null ' .

            // 10. component_lead in permission scheme

            'UNION DISTINCT ' .
            'SELECT user.id as user_id, user.first_name, user.last_name, user.email, user.notify_own_changes_flag ' .
            'from project ' .
            'left join notification_scheme on notification_scheme.id = project.notification_scheme_id ' .
            'left join notification_scheme_data on (notification_scheme_data.notification_scheme_id = notification_scheme.id and ' .
                       'notification_scheme_data.component_lead is not null) ' .
            'left join yongo_issue on yongo_issue.id = ? ' .
            'left join issue_component on issue_component.issue_id = yongo_issue.id ' .
            'left join project_component on project_component.id = issue_component.project_component_id ' .
            'left join user on user.id = project_component.leader_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
                'notification_scheme_data.event_id = ? and ' .
                'notification_scheme_data.component_lead is not null and ' .
                'project_component.leader_id is not null and ' .
                'user.id is not null ' .

            // 11. user picker multiple selection

            'UNION DISTINCT ' .
            'SELECT user.id as user_id, user.first_name, user.last_name, user.email, user.notify_own_changes_flag ' .
            'from project ' .
            'left join notification_scheme on notification_scheme.id = project.notification_scheme_id ' .
            'left join notification_scheme_data on (notification_scheme_data.notification_scheme_id = notification_scheme.id and ' .
            'notification_scheme_data.user_picker_multiple_selection is not null) ' .
            'left join yongo_issue on yongo_issue.id = ? ' .
            'left join issue_custom_field_data on (issue_custom_field_data.issue_id = yongo_issue.id and issue_custom_field_data.field_id = notification_scheme_data.user_picker_multiple_selection) ' .
            'left join user on user.id = issue_custom_field_data.value ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
            'notification_scheme_data.event_id = ? and ' .
            'user.id is not null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiiiiiiiiiiiiiiii", $eventId, $eventId, $eventId, $eventId, $issue['id'], $eventId, $issue['id'], $eventId, $eventId, $loggedInUserId, $issue['id'], $eventId, $eventId, $issue['id'], $eventId, $issue['id'], $eventId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getUsers($projectIdArray) {
        if (is_array($projectIdArray))
            $projectsSQL = '(' . implode(',', $projectIdArray) . ')';
        else
            $projectsSQL = '(' . $projectIdArray . ')';

        $query = 'SELECT DISTINCT user.id as user_id, user.first_name, user.last_name ' .
            'from project_role_data ' .
            'left join user on user.id = project_role_data.user_id ' .
            'where project_role_data.project_id IN ' . $projectsSQL;

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function userHasPermission($projectIdArray, $permissionId, $userId = null, $issueId = null) {

        if (is_array($projectIdArray)) {
            if (count($projectIdArray)) {
                $projectsSQL = '(' . implode(',', $projectIdArray) . ')';
            } else {
                return null;
            }
        } else {
            if (!empty($projectIdArray)) {
                $projectsSQL = '(' . $projectIdArray . ')';
            } else {
                return null;
            }
        }

            // 1. user in permission scheme
        $query = 'SELECT user.id as user_id, user.first_name, user.last_name ' .
            'from project ' .
            'left join permission_scheme on permission_scheme.id = project.permission_scheme_id ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
            'left join user on user.id = permission_scheme_data.user_id ' .
            'where project.id IN ' . $projectsSQL . ' and ' .
                'permission_scheme_data.user_id = ? and ' .
                'permission_scheme_data.sys_permission_id = ? ' .


            // 2. group in permission scheme

            'UNION ' .
            'SELECT user.id as user_id, user.first_name, user.last_name ' .
            'from project ' .
            'left join permission_scheme on permission_scheme.id = project.permission_scheme_id ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
            'left join `group` on group.id = permission_scheme_data.group_id ' .
            'left join `group_data` on group_data.group_id = `group`.id ' .
            'left join user on user.id = group_data.user_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
                'permission_scheme_data.group_id is not null and ' .
                'permission_scheme_data.sys_permission_id = ? and ' .
                'user.id = ? ' .


            // 3. permission role in permission scheme - user

            'UNION ' .
            'SELECT user.id as user_id, user.first_name, user.last_name ' .
            'from project ' .
            'left join permission_scheme on permission_scheme.id = project.permission_scheme_id ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
            'left join project_role_data on project_role_data.permission_role_id = permission_scheme_data.permission_role_id ' .
            'left join user on user.id = project_role_data.user_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
                'project_role_data.user_id is not null and ' .
                'permission_scheme_data.sys_permission_id = ? and ' .
                'user.id = ? ' .


            // 4. permission role in permission scheme - group

            'UNION ' .
            'SELECT user.id as user_id, user.first_name, user.last_name ' .
            'from project ' .
            'left join permission_scheme on permission_scheme.id = project.permission_scheme_id ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
            'left join project_role_data on project_role_data.permission_role_id = permission_scheme_data.permission_role_id ' .
            'left join `group` on group.id = project_role_data.group_id ' .
            'left join `group_data` on group_data.group_id = `group`.id ' .
            'left join user on user.id = group_data.user_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
                'project_role_data.group_id is not null and ' .
                'permission_scheme_data.sys_permission_id = ? and ' .
                'user.id = ? ' .

            // 5. reporter

            'UNION ' .
            'SELECT user.id as user_id, user.first_name, user.last_name ' .
            'from project ' .
            'left join permission_scheme on permission_scheme.id = project.permission_scheme_id ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
            'left join yongo_issue on yongo_issue.project_id = project.id ' .
            'left join user on user.id = yongo_issue.user_reported_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
            'permission_scheme_data.sys_permission_id = ? and ' .
            'permission_scheme_data.reporter = 1 and ' .
            'user.id = ? ';



        if ($issueId) {
            $query .= ' and yongo_issue.id = ? ';
        }

            // 6. assignee

        $query .=
            'UNION ' .
            'SELECT user.id as user_id, user.first_name, user.last_name ' .
            'from project ' .
            'left join permission_scheme on permission_scheme.id = project.permission_scheme_id ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
            'left join yongo_issue on yongo_issue.project_id = project.id ' .
            'left join user on user.id = yongo_issue.user_assigned_id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
            'permission_scheme_data.sys_permission_id = ? and ' .
            'permission_scheme_data.current_assignee = 1 and ' .
            'user.id = ? ';

        if ($issueId) {
            $query .= ' and yongo_issue.id = ? ';
        }

            // 7. check if there is a group 'Anyone' set in the permissions

        $query .=
            'UNION ' .
            'SELECT -1 as user_id, null as first_name, null as last_name ' .
            'from project ' .
            'left join permission_scheme on permission_scheme.id = project.permission_scheme_id ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = permission_scheme.id ' .
            'where project.id  IN ' . $projectsSQL . ' and ' .
            'permission_scheme_data.sys_permission_id = ? and ' .
            'permission_scheme_data.group_id = 0 ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($issueId) {
            $stmt->bind_param("iiiiiiiiiiiiiii", $userId, $permissionId, $permissionId, $userId, $permissionId, $userId, $permissionId, $userId, $permissionId, $userId, $issueId, $permissionId, $userId, $issueId, $permissionId);
        } else {
            $stmt->bind_param("iiiiiiiiiiiii", $userId, $permissionId, $permissionId, $userId, $permissionId, $userId, $permissionId, $userId, $permissionId, $userId, $permissionId, $userId, $permissionId);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getVersionById($versionId) {
        $query = "SELECT id, project_id, name, description FROM project_version WHERE id = ? LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $versionId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getComponentById($componentId) {
        $query = "SELECT project_component.id, leader_id, project_id, name, description, user.id as user_id, user.first_name, user.last_name " .
            "FROM project_component " .
            "LEFT JOIN user on user.id = project_component.leader_id " .
            "WHERE project_component.id = ? " .
            "LIMIT 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $componentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function addVersion($projectId, $name, $description, $date) {
        $query = "INSERT INTO project_version(project_id, name, description, date_created) VALUES (?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($projectId) {
            $stmt->bind_param("isss", $projectId, $name, $description, $date);
        }

        $stmt->execute();
    }

    public function updateComponentById($componentId, $name, $description, $leader, $date) {
        $query = 'UPDATE project_component SET ' .
                 'name = ?, description = ?, leader_id = ?, date_updated = ? ' .
                 'WHERE id = ? ' .
                 'LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ssisi", $name, $description, $leader, $date, $componentId);
        $stmt->execute();
    }

    public function updateVersionById($versionId, $name, $description, $date) {
        $query = 'UPDATE project_version SET ' .
                    'name = ?, description = ?, date_updated = ? ' .
                    'WHERE id = ? ' .
                    'LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("sssi", $name, $description, $date, $versionId);
        $stmt->execute();
    }

    public function getScreenData($project, $issueTypeId, $sysOperationId, $resultType = null) {
        $issueTypeScreenSchemeId = $project['issue_type_screen_scheme_id'];
        $issueTypeScreenSchemeData = UbirimiContainer::get()['repository']->get(IssueTypeScreenScheme::class)->getDataByIssueTypeScreenSchemeIdAndIssueTypeId($issueTypeScreenSchemeId, $issueTypeId);
        $screenSchemeId = $issueTypeScreenSchemeData['screen_scheme_id'];
        $screenSchemeData = UbirimiContainer::get()['repository']->get(ScreenScheme::class)->getDataByScreenSchemeIdAndSysOperationId($screenSchemeId, $sysOperationId);
        $screenId = $screenSchemeData['screen_id'];

        $screenData = UbirimiContainer::get()['repository']->get(Screen::class)->getDataById($screenId);
        if ($resultType == 'array') {
            $resultArray = array();
            while ($data = $screenData->fetch_array(MYSQLI_ASSOC)) {
                $resultArray[] = $data;
            }
            return $resultArray;
        } else
            return $screenData;
    }

    public function getFieldInformation($issueTypeFieldConfigurationId, $issueTypeId, $resultType = null) {
        $query = "SELECT field_configuration_data.field_id, field_configuration_data.visible_flag, field_configuration_data.required_flag, field_configuration_data.field_description, " .
                 "field.code as field_code " .
            "FROM issue_type_field_configuration_data " .
            "LEFT JOIN field_configuration_data on field_configuration_data.field_configuration_id = issue_type_field_configuration_data.field_configuration_id " .
            "left join field on field.id = field_configuration_data.field_id " .
            "WHERE issue_type_field_configuration_data.issue_type_field_configuration_id = ? and " .
            "issue_type_field_configuration_data.issue_type_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueTypeFieldConfigurationId, $issueTypeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultData = array();
                while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultData[] = $data;
                }
                return $resultData;
            } else return $result;
        } else
            return null;
    }

    public function getByWorkflowSchemeId($schemeId) {
        $query = 'SELECT * ' .
            'FROM project ' .
            'WHERE project.workflow_scheme_id = ? ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $schemeId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result;
        else
            return null;
    }

    public function getByIssueId($issueId) {
        $query = 'select project.id ' .
                 'from yongo_issue ' .
                 'left join project on project.id = yongo_issue.project_id ' .
                 'where yongo_issue.id = ? ' .
                 'limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return null;
    }

    public function getUnresolvedIssuesCountByProjectId($projectId) {
        $query = 'select count(yongo_issue.id) as count ' .
            'from yongo_issue ' .
            'where yongo_issue.project_id = ? ' .
            'and yongo_issue.resolution_id is null ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            $row_result = $result->fetch_array(MYSQLI_ASSOC);
            return $row_result['count'];
        }
        else
            return null;
    }

    public function getVersionByName($projectId, $name, $versionId = null) {
        $query = 'select id, name from project_version where project_id = ? and LOWER(name) = LOWER(?) ';
        if ($versionId) $query .= ' and id != ?';
        $query .= ' limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($versionId)
            $stmt->bind_param("isi", $projectId, $name, $versionId);
        else
            $stmt->bind_param("is", $projectId, $name);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result->fetch_array(MYSQLI_ASSOC);
        else
            return false;
    }

    public function getComponentByName($projectId, $name, $componentId = null) {
        $query = 'select id, name from project_component where project_id = ? and LOWER(name) = LOWER(?)';
        if ($componentId) $query .= ' and id != ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        if ($componentId)
            $stmt->bind_param("isi", $projectId, $name, $componentId);
        else
            $stmt->bind_param("is", $projectId, $name);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function deleteIssuesByProjectId($projectId) {
        $issues = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters(array('project' => $projectId));

        if ($issues) {
            while ($issue = $issues->fetch_array(MYSQLI_ASSOC)) {

                // delete issues from disk, if any
                Util::deleteDir(Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_YONGO) . $issue['id']);

                UbirimiContainer::get()['repository']->get(Issue::class)->deleteById($issue['id']);
            }
        }

        $query = "DELETE IGNORE from yongo_issue WHERE project_id = " . $projectId;
        UbirimiContainer::get()['db.connection']->query($query);
    }

    public function getByWorkflowIssueTypeScheme($clientId, $workflowIssueTypeSchemeId) {
        $query = 'select project.id, project.name ' .
                 'from workflow ' .
                 'left join workflow_scheme_data on workflow_scheme_data.workflow_id = workflow.id ' .
                 'left join project on project.workflow_scheme_id = workflow_scheme_data.workflow_scheme_id ' .
                 'where workflow.client_id = ? and ' .
                 'workflow.issue_type_scheme_id = ? and ' .
                 'project.id is not null ' .
                 'group by project.id';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $workflowIssueTypeSchemeId);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function getAll($filters = array()) {
        $query = 'select project.id, project.name, project.code, project.description, project.date_created, ' .
                 'client.company_name as client_company_name, client.contact_email ' .
                 'from project ' .
                 'left join client on client.id = project.client_id ' .
                 'where 1 = 1';

        if (!empty($filters['today'])) {
            $query .= " and DATE(project.date_created) = DATE(NOW())";
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

    public function updateIssueSecurityLevel($projectId, $oldSecurityLevel, $newSecurityLevel) {
        if ($newSecurityLevel == -1)
            $newSecurityLevel = null;

        $query = 'update yongo_issue set security_scheme_level_id = ? where project_id = ? and security_scheme_level_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $newSecurityLevel, $projectId, $oldSecurityLevel);
        $stmt->execute();
    }

    public function updateAllIssuesSecurityLevel($projectId, $newSecurityLevel) {
        $query = 'update yongo_issue set security_scheme_level_id = ? where project_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $newSecurityLevel, $projectId);
        $stmt->execute();
    }

    public function setIssueSecuritySchemeId($projectId, $projectIssueSecuritySchemeId) {
        $query = 'update project set issue_security_scheme_id = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $projectIssueSecuritySchemeId, $projectId);
        $stmt->execute();
    }

    public function getIssuesWithNoSecurityScheme($projectId) {
        $query = 'select * from yongo_issue where project_id = ? and security_scheme_level_id is null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $projectId);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function updateIssueSecurityLevelForUnsercuredIssues($projectId, $newSecurityLevel) {
        $query = 'update yongo_issue set security_scheme_level_id = ? where project_id = ? and security_scheme_level_id is null';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $newSecurityLevel, $projectId);
        $stmt->execute();
    }

    public function updateIssuesSecurityLevel($projectId, $oldNewLevel, $date) {
        $issueIdsLevel = array();
        for ($i = 0; $i < count($oldNewLevel); $i++) {

            $issues = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters(array('project' => $projectId, 'security_scheme_level' => $oldNewLevel[$i][0]));
            while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                $issueIdsLevel[] = array($issue['id'], $oldNewLevel[$i][1]);
            }
        }

        for ($i = 0; $i < count($issueIdsLevel); $i++) {

            UbirimiContainer::get()['repository']->get(Issue::class)->updateById($issueIdsLevel[$i][0], array(Field::FIELD_ISSUE_SECURITY_LEVEL_CODE => $issueIdsLevel[$i][1]), $date);
        }
    }

    public function updateLastIssueNumber($projectId, $newIssueNumber) {
        $query = 'update project set issue_number = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $newIssueNumber, $projectId);
        $stmt->execute();
    }

    public function updatePermissionScheme($projectId, $permissionSchemeId) {
        $query = 'update project set permission_scheme_id = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $permissionSchemeId, $projectId);
        $stmt->execute();
    }

    public function updateNotificationScheme($projectId, $notificationSchemeId) {
        $query = 'update project set notification_scheme_id = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $notificationSchemeId, $projectId);
        $stmt->execute();
    }

    public function updateFieldConfigurationScheme($projectId, $fieldConfigurationSchemeId) {
        $query = 'update project set issue_type_field_configuration_id = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $fieldConfigurationSchemeId, $projectId);
        $stmt->execute();
    }

    public function updateIssueTypeScreenScheme($projectId, $issueTypeScreenSchemeId) {
        $query = 'update project set issue_type_screen_scheme_id = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueTypeScreenSchemeId, $projectId);
        $stmt->execute();
    }

    public function getByWorkflowId($workflowId) {
        $query = 'select project.id ' .
                 'from project ' .
                 'left join workflow_scheme on workflow_scheme.id = project.workflow_scheme_id ' .
                 'left join workflow_scheme_data on workflow_scheme_data.workflow_scheme_id = workflow_scheme.id ' .
                 'where workflow_scheme_data.workflow_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $workflowId);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function getTotalIssuesPreviousDate($clientId, $projectId, $date, $helpdeskFlag = 0) {

        $query = 'SELECT yongo_issue.id, resolution_id
            FROM yongo_issue
            left join project on project.id = yongo_issue.project_id
            left join client on client.id = project.client_id
            WHERE DATE(yongo_issue.date_created) <= ?';

        if (-1 != $projectId) {
            $query .= ' AND yongo_issue.project_id = ?';
        } else {
            $query .= ' AND client.id = ' . $clientId;
        }

        if ($helpdeskFlag) {
            $query .= ' AND yongo_issue.helpdesk_flag = 1';
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        if (-1 != $projectId) {
            $stmt->bind_param("si", $date, $projectId);
        } else {
            $stmt->bind_param("s", $date);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            return $result->num_rows;
        }

        return 0;
    }

    public function getTotalIssuesResolvedOnDate($clientId, $projectId, $date, $helpdeskFlag = 0) {
        $query = 'SELECT yongo_issue.id, resolution_id
                FROM yongo_issue
                left join project on project.id = yongo_issue.project_id
                left join client on client.id = project.client_id
                WHERE DATE(yongo_issue.date_updated) <= ? AND resolution_id IS NOT NULL';

        if (-1 != $projectId) {
            $query .= ' AND yongo_issue.project_id = ?';
        } else {
            $query .= ' AND client.id = ' . $clientId;
        }

        if ($helpdeskFlag) {
            $query .= ' and yongo_issue.helpdesk_flag = 1';
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        if (-1 != $projectId) {
            $stmt->bind_param("si", $date, $projectId);
        } else {
            $stmt->bind_param("s", $date);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result->num_rows;
        }

        return 0;
    }

    public function getParentComponent($parentComponentId, $resultType = null) {
        $query = 'select id as project_component_id, parent_id, name ' .
            'from project_component ' .
            'where id = ? order by id desc';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $parentComponentId);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                return $result->fetch_array(MYSQLI_ASSOC);
            } else {
                return $result;
            }
        } else
            return 0;
    }

    public function checkProjectsBelongToClient($clientId, $projectIds) {
        for ($pos = 0; $pos < count($projectIds); $pos++) {
            $projectFilter = UbirimiContainer::get()['repository']->get(YongoProject::class)->getById($projectIds[$pos]);

            if ($projectFilter['client_id'] != $clientId) {
                return false;
            }
        }

        return true;
    }

    public function addDefaultInitialDataForHelpDesk($clientId, $projectId, $userId, $currentDate) {
        // add the default queues
        // -----------------------------------------------
        $defaultColumns = 'code#summary#priority#status#created#updated#reporter#assignee';

        // queue 1: my open tickets
        $queueDefinition = 'assignee=currentUser() AND status = Open AND resolution = Unresolved';
        UbirimiContainer::get()['repository']->get(Queue::class)->save($userId, $projectId, 'My Open Tickets', 'My Open Tickets', $queueDefinition, $defaultColumns, $currentDate);

        // queue 2: need triage
        $queueDefinition = 'status = Open AND resolution = Unresolved';
        UbirimiContainer::get()['repository']->get(Queue::class)->save($userId, $projectId, 'Needs Triage', 'Needs Triage', $queueDefinition, $defaultColumns, $currentDate);

        // queue 3: sla at risk
        $queueDefinition = 'resolution = Unresolved AND (Time waiting for support < 30 AND Time waiting for support > 0 OR Time to resolution < 30 AND Time to resolution > 0)';
        UbirimiContainer::get()['repository']->get(Queue::class)->save($userId, $projectId, 'SLA at risk', 'SLA at risk', $queueDefinition, $defaultColumns, $currentDate);

        // queue 4: sla at risk
        $queueDefinition = 'resolution = Unresolved AND (Time waiting for support < 0 OR Time to resolution < 0)';
        UbirimiContainer::get()['repository']->get(Queue::class)->save($userId, $projectId, 'SLA breached', 'SLA breached', $queueDefinition, $defaultColumns, $currentDate);

        // add the default SLA calendar
        $dataDefaultCalendar = array();
        for ($i = 0; $i < 7; $i++) {
            $dataDefaultCalendar[$i]['notWorking'] = 0;
            $dataDefaultCalendar[$i]['from_hour'] = '00';
            $dataDefaultCalendar[$i]['from_minute'] = '00';
            $dataDefaultCalendar[$i]['to_hour'] = '23';
            $dataDefaultCalendar[$i]['to_minute'] = '59';
        }

        $defaultSLACalendarId = UbirimiContainer::get()['repository']->get(SlaCalendar::class)->addCalendar($projectId, 'Default 24/7 Calendar', 'Default 24/7 Calendar', $dataDefaultCalendar, 1, $currentDate);

        // add the default SLAs

        // sla 1: time to first response
        $status = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getByName($clientId, 'status', 'In Progress');
        $slaId = UbirimiContainer::get()['repository']->get(Sla::class)->save($projectId, 'Time to first response', 'Time to first response', 'start_issue_created', 'stop_status_set_' . $status['id'], $currentDate);

        // sla 2: time to resolution
        $slaId = UbirimiContainer::get()['repository']->get(Sla::class)->save($projectId, 'Time to resolution', 'Time to resolution', 'start_issue_created', 'stop_resolution_set', $currentDate);
        UbirimiContainer::get()['repository']->get(Sla::class)->addGoal($slaId, $defaultSLACalendarId, 'priority = Blocker', '', 1440);

        // sla 3: time waiting for support
        $slaId = UbirimiContainer::get()['repository']->get(Sla::class)->save($projectId, 'Time waiting for support', 'Time waiting for support', 'start_issue_created', 'stop_resolution_set', $currentDate);
        UbirimiContainer::get()['repository']->get(Sla::class)->addGoal($slaId, $defaultSLACalendarId, 'priority = Blocker', '', 24);
        UbirimiContainer::get()['repository']->get(Sla::class)->addGoal($slaId, $defaultSLACalendarId, 'priority = Critical', '', 96);

        $issues = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters(array('project' => $projectId));
        if ($issues) {
            while ($issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                UbirimiContainer::get()['repository']->get(Issue::class)->addPlainSLAData($issue['id'], $projectId);
            }
        }
    }

    public function removeHelpdeskData($projectId) {
        $slas = UbirimiContainer::get()['repository']->get(Sla::class)->getByProjectId($projectId);
        while ($slas && $sla = $slas->fetch_array(MYSQLI_ASSOC)) {
            UbirimiContainer::get()['repository']->get(Sla::class)->deleteById($sla['id']);
        }

        $calendars = UbirimiContainer::get()['repository']->get(SlaCalendar::class)->getByProjectId($projectId);
        while ($calendars && $calendar = $calendars->fetch_array(MYSQLI_ASSOC)) {
            UbirimiContainer::get()['repository']->get(SlaCalendar::class)->deleteById($calendar['id']);
        }

        $queues = UbirimiContainer::get()['repository']->get(Queue::class)->getByProjectId($projectId);
        while ($queues && $queue = $queues->fetch_array(MYSQLI_ASSOC)) {
            UbirimiContainer::get()['repository']->get(Queue::class)->deleteById($queue['id']);
        }
    }

    public function toggleHelpDeskFlag($projectId) {
        $query = 'update project set help_desk_enabled_flag = 1 - help_desk_enabled_flag where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
    }

    public function getWorkDoneDistributition($projectId, $dateFrom, $dateTo, $resultType = null) {
        $query = 'SELECT issue_type.name as type_name, user.first_name, user.last_name, COUNT(yongo_issue.id) as total ' .
                 'FROM yongo_issue ' .
                 'LEFT JOIN user ON user.id = yongo_issue.user_assigned_id ' .
                 'LEFT JOIN issue_type ON issue_type.id = yongo_issue.type_id ' .
                 'WHERE yongo_issue.project_id = ? ' .
                 'and yongo_issue.resolution_id is not null ' .
                 'and yongo_issue.date_resolved >= ? and yongo_issue.date_resolved <= ? ' .
                 'GROUP BY type_id, user_assigned_id ';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iss", $projectId, $dateFrom, $dateTo);

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            if ($resultType == 'array') {
                $resultData = array();
                while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultData[] = $data;
                }
                return $resultData;
            } else {
                return $result;
            }
        } else
            return 0;
    }
}
