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

use Ubirimi\Agile\Repository\Board\Board;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class Issue
{
    const ISSUE_AFFECTED_VERSION_FLAG = 1;
    const ISSUE_FIX_VERSION_FLAG = 2;

    public function getById($issueId, $loggedInUserId = null) {
        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters($issueQueryParameters, $loggedInUserId);

        return $issue;
    }

    public function getByParameters($parameters, $securitySchemeUserId = null, $queryWherePart = null, $loggedInUserId = null) {

        $parameterType = '';
        $parameterArray = array();

        $query = 'SELECT SQL_CALC_FOUND_ROWS issue_main_table.id, issue_main_table.nr, issue_main_table.summary, issue_main_table.description, issue_main_table.environment, ' .
            'user_reported.id as reporter, user_reported.first_name as ur_first_name, user_reported.last_name as ur_last_name, user_reported.avatar_picture as reporter_avatar_picture, ' .
            'user_assigned.first_name as ua_first_name, user_assigned.last_name as ua_last_name, user_assigned.id as assignee, user_assigned.avatar_picture as assignee_avatar_picture, ' .
            'project.code as project_code, project.name as project_name, issue_main_table.project_id as issue_project_id, issue_type.id as issue_type_id, issue_type.icon_name as issue_type_icon_name, issue_type.description as issue_type_description, ' .
            'issue_priority.id as priority, issue_priority.color as priority_color, issue_priority.icon_name as issue_priority_icon_name, issue_priority.description as issue_priority_description, issue_priority.name as priority_name, ' .
            'issue_status.id as status, issue_status.name as status_name, ' .
            'issue_type.id as type, issue_type.name as type_name, ' .
            'issue_resolution.name as resolution_name, issue_main_table.resolution_id as resolution, issue_main_table.parent_id, issue_main_table.security_scheme_level_id as security_level, ' .
            'issue_security_scheme_level.name as security_level_name, ' .
            'issue_main_table.user_assigned_id as issue_assignee, ' .
            'issue_main_table.original_estimate, issue_main_table.remaining_estimate, issue_main_table.user_reported_ip, ';

        if (isset($parameters['sprint'])) {

            $query .= 'issue_parent.status_id as parent_status_id, issue_parent.user_assigned_id as parent_assignee, ' .
                      'issue_parent.nr as parent_nr, issue_parent.summary as parent_summary, project_parent.code as parent_project_code, ' .
                      'CONCAT(coalesce(issue_parent.id, \'z\'), issue_main_table.id) as sort_sprint, ';
        }

        if ($securitySchemeUserId) {
            // deal with security scheme level

            // 1. user in security scheme level data
            $query .= '(SELECT max(issue_security_scheme_level_data.id)
                      from issue_security_scheme_level_data
                      left join general_user on general_user.id = issue_security_scheme_level_data.user_id
                      where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id
                      and general_user.id = ?) as security_check1, ';

            $parameterType .= 'i';
            $parameterArray[] = $securitySchemeUserId;

            // 2. user in group security scheme level data
            $query .= '(SELECT max(issue_security_scheme_level_data.id) ' .
                'from issue_security_scheme_level_data ' .
                'left join `general_group` on general_group.id = issue_security_scheme_level_data.group_id ' .
                'left join `general_group_data` on general_group_data.group_id = `general_group`.id ' .
                'left join general_user on general_user.id = general_group_data.user_id ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id and ' .
                'general_user.id = ?) as security_check2, ';

            $parameterType .= 'i';
            $parameterArray[] = $securitySchemeUserId;

            // 3. permission role in security scheme level data - user
            $query .= '(SELECT max(issue_security_scheme_level_data.id) ' .
                'from issue_security_scheme_level_data ' .
                'left join project_role_data on project_role_data.permission_role_id = issue_security_scheme_level_data.permission_role_id ' .
                'left join general_user on general_user.id = project_role_data.user_id ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id and ' .
                'general_user.id = ?) as security_check3, ';

            $parameterType .= 'i';
            $parameterArray[] = $securitySchemeUserId;

            // 4. permission role in security scheme level data - group
            $query .= '(SELECT max(issue_security_scheme_level_data.id) ' .
                'from issue_security_scheme_level_data ' .
                'left join project_role_data on project_role_data.permission_role_id = issue_security_scheme_level_data.permission_role_id ' .
                'left join `general_group` on general_group.id = project_role_data.group_id ' .
                'left join `general_group_data` on general_group_data.group_id = `general_group`.id ' .
                'left join general_user on general_user.id = general_group_data.user_id ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id and ' .
                'general_user.id = ?) as security_check4, ';

            $parameterType .= 'i';
            $parameterArray[] = $securitySchemeUserId;

            // 5. current_assignee in security scheme level data
            $query .= '(SELECT max(issue_security_scheme_level_data.id) ' .
                'from issue_security_scheme_level_data, general_user ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id and ' .
                'issue_security_scheme_level_data.current_assignee is not null and ' .
                'issue_main_table.user_assigned_id is not null and ' .
                'issue_main_table.user_assigned_id = general_user.id and ' .
                'general_user.id = ?) as security_check5, ';

            $parameterType .= 'i';
            $parameterArray[] = $securitySchemeUserId;

            // 6. reporter in security scheme level data
            $query .= '(SELECT max(issue_security_scheme_level_data.id) ' .
                'from issue_security_scheme_level_data, general_user ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id and ' .
                'issue_security_scheme_level_data.reporter is not null and ' .
                'issue_main_table.user_reported_id is not null and ' .
                'issue_main_table.user_reported_id = general_user.id and ' .
                'general_user.id = ?) as security_check6, ';

            $parameterType .= 'i';
            $parameterArray[] = $securitySchemeUserId;

            // 7. project_lead in security scheme level data

            $query .= '(SELECT max(issue_security_scheme_level_data.id) ' .
                'from issue_security_scheme_level_data, project, general_user ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id and ' .
                'project.id = issue_main_table.project_id and ' .
                'project.lead_id = general_user.id and ' .
                'issue_security_scheme_level_data.project_lead is not null and ' .
                'project.lead_id is not null and ' .
                'general_user.id = ?) as security_check7, ';

            $parameterType .= 'i';
            $parameterArray[] = $securitySchemeUserId;
        }

        $query .=
            'issue_main_table.date_created, issue_main_table.date_updated, issue_main_table.date_resolved, issue_main_table.date_due as due_date ' .
            'from yongo_issue as issue_main_table ' .
            'LEFT JOIN issue_priority ON issue_main_table.priority_id = issue_priority.id ' .
            'LEFT JOIN issue_type ON issue_main_table.type_id = issue_type.id ' .
            'LEFT JOIN issue_status ON issue_main_table.status_id = issue_status.id ' .
            'LEFT JOIN issue_resolution ON issue_main_table.resolution_id = issue_resolution.id ' .
            'LEFT JOIN issue_component ON issue_main_table.id = issue_component.issue_id ' .
            'LEFT JOIN issue_version ON issue_main_table.id = issue_version.issue_id ' .
            'LEFT JOIN project ON issue_main_table.project_id = project.id ' .
            'left join permission_scheme_data on permission_scheme_data.permission_scheme_id = project.permission_scheme_id ' .
            'LEFT join general_user AS user_reported ON issue_main_table.user_reported_id = user_reported.id ' .
            'LEFT join general_user AS user_assigned ON issue_main_table.user_assigned_id = user_assigned.id ' .
            'LEFT JOIN issue_security_scheme_level ON issue_security_scheme_level.id = issue_main_table.security_scheme_level_id ' .
            'LEFT JOIN yongo_issue_sla ON yongo_issue_sla.yongo_issue_id = issue_main_table.id ';

        if (isset($parameters['backlog'])) {
            $query .= 'LEFT JOIN agile_board_sprint_issue ON agile_board_sprint_issue.issue_id = issue_main_table.id ';
            $query .= 'LEFT JOIN agile_board_sprint ON agile_board_sprint.id = agile_board_sprint_issue.agile_board_sprint_id ';
        }

        if (isset($parameters['sprint'])) {
            $query .= 'LEFT JOIN agile_board_sprint_issue ON agile_board_sprint_issue.issue_id = issue_main_table.id ';
            $query .= 'LEFT JOIN yongo_issue issue_parent on issue_parent.id = issue_main_table.parent_id ';
            $query .= 'LEFT JOIN project project_parent on project_parent.id = issue_parent.project_id ';
        }

        $queryWhere = '';

        if (isset($parameters['search_query']) && $parameters['search_query'] != '') {
            $query_where_part_arr = array();

            if (isset($parameters['summary_flag']) && $parameters['summary_flag'] == 1) {
                $query_where_part_arr[] = " issue_main_table.summary LIKE ? ";
                $parameterType .= 's';
                $parameterArray[] = "%" . $parameters['search_query'] . "%";
            }
            if (isset($parameters['description_flag']) && $parameters['description_flag'] == 1) {
                $query_where_part_arr[] = " issue_main_table.description LIKE ? ";
                $parameterType .= 's';
                $parameterArray[] = "%" . $parameters['search_query'] . "%";
            }

            // also search in assignee and reporter
            $query_where_part_arr[] = " CONCAT(user_assigned.first_name, user_assigned.last_name) LIKE ? ";
            $parameterType .= 's';
            $parameterArray[] = "%" . $parameters['search_query'] . "%";
            $query_where_part_arr[] = " CONCAT(user_reported.first_name, user_reported.last_name) LIKE ? ";
            $parameterType .= 's';
            $parameterArray[] = "%" . $parameters['search_query'] . "%";

            if ($query_where_part_arr) {
                $queryWhere .= '(' . implode(' OR ', $query_where_part_arr) . ') AND ';
            }
        }

        if (isset($parameters['project'])) {

            if (is_array($parameters['project'])) {

                $projectWithAssigneeReporterBrowsePermission = array();

                $queryProjectPartReporter = array();
                $queryProjectPartAssignee = array();

                for ($i = 0; $i <count($parameters['project']); $i++) {
                    $permissions = UbirimiContainer::get()['repository']->get(PermissionScheme::class)->getDataByProjectIdAndPermissionId($parameters['project'][$i], Permission::PERM_BROWSE_PROJECTS);

                    while ($permissions && $permission = $permissions->fetch_array(MYSQLI_ASSOC)) {

                        if ($permission['reporter'] == 1) {
                            $queryProjectPartReporter[] = '(issue_main_table.user_reported_id = ' . $loggedInUserId . ' and issue_main_table.project_id = ' . $parameters['project'][$i] . ')';
                            $projectWithAssigneeReporterBrowsePermission[] = $parameters['project'][$i];
                        }

                        if ($permission['current_assignee'] == 1) {
                            $queryProjectPartAssignee[] = '(issue_main_table.user_assigned_id = ' . $loggedInUserId . ' and issue_main_table.project_id =  ' . $parameters['project'][$i] . ')';
                            $projectWithAssigneeReporterBrowsePermission[] = $parameters['project'][$i];
                        }
                    }
                }

                $projectWithAssigneeReporterBrowsePermission = array_unique($projectWithAssigneeReporterBrowsePermission);

                $queryWhereReporter = '';
                $queryWhereAssignee = '';
                if (count($queryProjectPartReporter)) {
                    $queryWhereReporter = '(' . implode(' OR ', $queryProjectPartReporter) . ')';
                }
                if (count($queryProjectPartAssignee)) {
                    $queryWhereAssignee = '(' . implode(' OR ', $queryProjectPartAssignee) . ')';
                }

                $queryPartReporterAssignee = array();
                if (!empty($queryWhereReporter)) {
                    $queryPartReporterAssignee[] = $queryWhereReporter;
                }
                if (!empty($queryWhereAssignee)) {
                    $queryPartReporterAssignee[] = $queryWhereAssignee;
                }

                if (count($projectWithAssigneeReporterBrowsePermission)) {
                    if (Util::array_equal($parameters['project'], $projectWithAssigneeReporterBrowsePermission)) {
                        $queryWhere .= ' (issue_main_table.project_id IN (' . implode(',', $parameters['project']) . ') OR ';
                    } else {
                        $queryWhere .= ' (issue_main_table.project_id IN (' . implode(',', array_diff($parameters['project'], $projectWithAssigneeReporterBrowsePermission)) . ') OR ';
                    }

                    $queryWhere .= '(' . implode(' OR ', $queryPartReporterAssignee) . ')) ';
                    $queryWhere .= ' AND ';
                } else {
                    $queryWhere .= ' issue_main_table.project_id IN (' . implode(',', $parameters['project']) . ') AND ';
                }
            } else {
                $queryWhere .= ' issue_main_table.project_id = ? AND ';
                $parameterType .= 'i';
                $parameterArray[] = $parameters['project'];
            }
        }

        if (isset($parameters['issue_id'])) {

            if (is_array($parameters['issue_id'])) {
                $queryWhere .= ' issue_main_table.id IN (' . implode(', ', $parameters['issue_id']) . ') AND ';
            } else {
                $queryWhere .= ' issue_main_table.id = ? AND ';
                $parameterType .= 'i';
                $parameterArray[] = $parameters['issue_id'];
            }
        }

        if (isset($parameters['nr'])) {
            $queryWhere .= ' issue_main_table.nr = ? AND ';
            $parameterType .= 'i';
            $parameterArray[] = $parameters['nr'];
        }

        if (isset($parameters['code_nr'])) {

            $queryWhere .= " CONCAT(project.code, '-', issue_main_table.nr) = ? AND ";
            $parameterType .= 's';
            $parameterArray[] = $parameters['code_nr'];
        }

        if (isset($parameters['security_scheme_level'])) {
            if ($parameters['security_scheme_level'] == -1) {
                $queryWhere .= ' issue_main_table.security_scheme_level_id IS NULL AND ';
            } else {
                $queryWhere .= ' issue_main_table.security_scheme_level_id = ? AND ';
                $parameterType .= 'i';
                $parameterArray[] = $parameters['security_scheme_level'];
            }
        }

        if (isset($parameters['sprint'])) {
            $queryWhere .= ' agile_board_sprint_issue.id is not null and agile_board_sprint_issue.agile_board_sprint_id = ? and ';
            $parameterType .= 'i';
            $parameterArray[] = $parameters['sprint'];
        }

        if (isset($parameters['board_id'])) {
            $queryWhere .= ' agile_board_sprint.agile_board_id = ? AND ';
            $parameterType .= 'i';
            $parameterArray[] = $parameters['board_id'];
        }

        if (isset($parameters['parent_id'])) {
            if (is_array($parameters['parent_id'])) {
                $queryWhere .= ' issue_main_table.parent_id IN (' . implode(', ', $parameters['parent_id']) . ') AND ';
            } else {
                $queryWhere .= ' issue_main_table.parent_id = ? AND ';
                $parameterType .= 'i';
                $parameterArray[] = $parameters['parent_id'];
            }
        }

        if (isset($parameters['backlog'])) {
            $queryWhere .= ' issue_main_table.parent_id is null and agile_board_sprint_issue.issue_id IS NULL and ';
        }

        if (isset($parameters['helpdesk_flag'])) {
            $queryWhere .= ' issue_main_table.helpdesk_flag = 1 and ';
        }

        if (isset($parameters['component'])) {
            if (is_array($parameters['component'])) {
                if (!in_array(-1, $parameters['component']))
                    $queryWhere .= ' issue_component.project_component_id IN (' . implode(',', $parameters['component']) . ') AND ';
            } else {
                if ($parameters['component'] != -1) {
                    $queryWhere .= ' issue_component.project_component_id = ? AND ';
                    $parameterType .= 'i';
                    $parameterArray[] = $parameters['component'];
                } else {
                    $queryWhere .= ' issue_component.project_component_id is null and ';
                }
            }
        }

        if (isset($parameters['version'])) {
            if (is_array($parameters['version'])) {
                if (!in_array(-1, $parameters['version']))
                    $queryWhere .= ' issue_version.project_version_id IN (' . implode(',', $parameters['version']) . ') AND ';
            } else {
                $queryWhere .= ' issue_version.project_version_id = ? AND ';
                $parameterType .= 'i';
                $parameterArray[] = $parameters['version'];
            }
        }

        if (isset($parameters['fix_version'])) {
            if (is_array($parameters['fix_version'])) {
                if (!in_array(-1, $parameters['fix_version']))
                    $queryWhere .= ' issue_version.project_version_id IN (' . implode(',', $parameters['fix_version']) . ') AND issue_version.affected_targeted_flag = ' . Issue::ISSUE_FIX_VERSION_FLAG . ' AND ';
            } else {
                $queryWhere .= ' issue_version.project_version_id = ? AND issue_version.affected_targeted_flag = ' . Issue::ISSUE_FIX_VERSION_FLAG . ' AND ';
                $parameterType .= 'i';
                $parameterArray[] = $parameters['fix_version'];
            }
        }

        if (isset($parameters['affects_version'])) {
            if (is_array($parameters['affects_version'])) {
                if (!in_array(-1, $parameters['affects_version']))
                    $queryWhere .= ' issue_version.project_version_id IN (' . implode(',', $parameters['affects_version']) . ') AND issue_version.affected_targeted_flag = ' . Issue::ISSUE_AFFECTED_VERSION_FLAG . ' AND ';
            } else {
                $queryWhere .= ' issue_version.project_version_id = ? AND issue_version.affected_targeted_flag = ' . Issue::ISSUE_FIX_VERSION_FLAG . ' AND ';
                $parameterType .= 'i';
                $parameterArray[] = $parameters['affects_version'];
            }
        }

        if (array_key_exists('assignee', $parameters)) {

            if (is_array($parameters['assignee'])) {
                for ($index = 0; $index < count($parameters['assignee']); $index++) {
                    if ($parameters['assignee'][$index] == 'current_user') {
                        $parameters['assignee'][$index] = $securitySchemeUserId;
                    }
                }
                if (!in_array(-1, $parameters['assignee']))
                    $queryWhere .= ' issue_main_table.user_assigned_id IN (' . implode(',', $parameters['assignee']) . ') AND ';
            } else {
                if ($parameters['assignee']) {
                    if ($parameters['assignee'] == 'current_user') {
                        $parameters['assignee'] = $securitySchemeUserId;
                    }

                    $queryWhere .= ' issue_main_table.user_assigned_id = ? AND ';
                    $parameterType .= 'i';
                    $parameterArray[] = $parameters['assignee'];
                } else if ($parameters['assignee'] === 0) {
                    $queryWhere .= ' issue_main_table.user_assigned_id is null and ';
                }
            }
        }

        if (array_key_exists('not_assignee', $parameters)) {
            $queryWhere .= ' issue_main_table.user_assigned_id != ? AND ';
            $parameterType .= 'i';
            $parameterArray[] = $parameters['not_assignee'];
        }

        if (array_key_exists('date_due', $parameters)) {
            $queryWhere .= ' issue_main_table.date_due = ? AND ';
            $parameterType .= 's';
            $parameterArray[] = $parameters['date_due'];
        }

        if (array_key_exists('date_due_after', $parameters) && isset($parameters['date_due_after'])) {
            $queryWhere .= ' issue_main_table.date_due >= ? AND ';
            $parameterType .= 's';
            $parameterArray[] = $parameters['date_due_after'];
        }

        if (array_key_exists('date_due_before', $parameters) && isset($parameters['date_due_before'])) {
            $queryWhere .= ' issue_main_table.date_due <= ? AND ';
            $parameterType .= 's';
            $parameterArray[] = $parameters['date_due_before'];
        }

        if (array_key_exists('date_created_after', $parameters) && isset($parameters['date_created_after'])) {
            $queryWhere .= ' issue_main_table.date_created >= ? AND ';
            $parameterType .= 's';
            $parameterArray[] = $parameters['date_created_after'];
        }

        if (array_key_exists('date_updated_after', $parameters) && isset($parameters['date_updated_after'])) {
            $queryWhere .= ' issue_main_table.date_updated >= ? AND ';
            $parameterType .= 's';
            $parameterArray[] = $parameters['date_updated_after'];
        }

        if (array_key_exists('date_created_before', $parameters) && isset($parameters['date_created_before'])) {
            $queryWhere .= ' issue_main_table.date_created <= ? AND ';
            $parameterType .= 's';
            $parameterArray[] = $parameters['date_created_before'];
        }

        if (isset($parameters['reporter'])) {
            if (is_array($parameters['reporter'])) {
                if (!in_array(-1, $parameters['reporter']))
                    $queryWhere .= ' issue_main_table.user_reported_id IN (' . implode(',', $parameters['reporter']) . ') AND ';
            } else {
                $queryWhere .= ' issue_main_table.user_reported_id = ? AND ';
                $parameterType .= 'i';
                $parameterArray[] = $parameters['reporter'];
            }
        }

        if (isset($parameters['client_id'])) {
            $queryWhere .= ' project.client_id = ? AND ';
            $parameterType .= 'i';
            $parameterArray[] = $parameters['client_id'];
        }

        if (isset($parameters['type'])) {

            if (is_array($parameters['type'])) {
                if (!in_array(-1, $parameters['type']))
                    $queryWhere .= ' issue_main_table.type_id IN (' . implode(',', $parameters['type']) . ') AND ';
            } else {
                $queryWhere .= ' issue_main_table.type_id = ? AND ';
                $parameterType .= 'i';
                $parameterArray[] = $parameters['type'];
            }
        }

        if (isset($parameters['priority'])) {
            if (is_array($parameters['priority'])) {
                if (!in_array(-1, $parameters['priority']))
                    $queryWhere .= ' issue_main_table.priority_id IN (' . implode(',', $parameters['priority']) . ') AND ';
            } else {
                $queryWhere .= ' issue_main_table.priority_id = ? AND ';
                $parameterType .= 'i';
                $parameterArray[] = $parameters['priority'];
            }
        }

        if (isset($parameters['status'])) {
            if (is_array($parameters['status'])) {
                if (!in_array(-1, $parameters['status']))
                    $queryWhere .= ' issue_main_table.status_id IN (' . implode(',', $parameters['status']) . ') AND ';
            } else {
                $queryWhere .= ' issue_main_table.status_id = ? AND ';
                $parameterType .= 'i';
                $parameterArray[] = $parameters['status'];
            }
        }

        if (isset($parameters['not_status'])) {
            if (is_array($parameters['not_status'])) {
                $queryWhere .= ' issue_main_table.status_id NOT IN (' . implode(',', $parameters['not_status']) . ') AND ';
            } else {
                $queryWhere .= ' issue_main_table.status_id != ? AND ';
                $parameterType .= 'i';
                $parameterArray[] = $parameters['not_status'];
            }
        }

        if (isset($parameters['resolution'])) {
            $includeUnresolvedIssues = false;
            $includeAllResolutions = false;
            // any resolution
            $index = array_search(-1, $parameters['resolution']);
            if ($index !== false) {
                $includeAllResolutions = true;
                unset($parameters['resolution'][$index]);

            }

            // unresolved issues
            $index = array_search(-2, $parameters['resolution']);
            if ($index !== false) {
                $includeUnresolvedIssues = true;
                unset($parameters['resolution'][$index]);
            }

            $queryResolutionPart = array();

            if ($includeAllResolutions)
                $queryResolutionPart[] = ' issue_main_table.resolution_id IS NOT NULL ';
            if (count($parameters['resolution']))
                $queryResolutionPart[] = ' issue_main_table.resolution_id IN (' . implode(',', $parameters['resolution']) . ') ';
            if ($includeUnresolvedIssues)
                $queryResolutionPart[] = ' issue_main_table.resolution_id IS NULL ';

            if (count($queryResolutionPart))
                $queryWhere .= '( ' . implode(' OR ', $queryResolutionPart) . ' ) ';
        }


        if (strtoupper(substr($queryWhere, strlen($queryWhere) - 4, 4)) == 'AND ') {
            $queryWhere = substr($queryWhere, 0, strlen($queryWhere) - 4);
        }
        if (strtoupper(substr($queryWherePart, strlen($queryWherePart) - 4, 4)) == 'AND ') {
            $queryWherePart = substr($queryWherePart, 0, strlen($queryWherePart) - 4);
        }

        $sortColumns = array();
        if (isset($parameters['sort'])) {
            switch ($parameters['sort']) {
                case 'code':
                    $sortColumns[] = 'issue_main_table.id';
                    break;
                case 'type':
                    $sortColumns[] = 'type';
                    break;
                case 'priority':
                    $sortColumns[] = 'priority';
                    break;
                case 'status':
                    $sortColumns[] = 'status';
                    break;
                case 'reporter':
                    $sortColumns[] = 'ur_first_name';
                    $sortColumns[] = 'ur_last_name';
                    break;
                case 'summary':
                    $sortColumns[] = 'issue_main_table.summary';
                    break;
                case 'reported_by':
                    $sortColumns[] = 'user_reported.id';
                    break;
                case 'assignee':
                    $sortColumns[] = 'ua_first_name';
                    $sortColumns[] = 'ua_last_name';
                    break;
                case 'created':
                    $sortColumns[] = 'issue_main_table.date_created';
                    break;
                case 'updated':
                    $sortColumns[] = 'issue_main_table.date_updated';
                    break;
                case 'parent':
                    $sortColumns[] = 'issue_main_table.parent_id';
                    break;
                case 'sprint':
                    $sortColumns[] = 'sort_sprint';
                    break;
            }
        } else {
            $sortColumns[] = 'issue_main_table.date_created';
        }
        if ($queryWherePart) {
            $queryWhere .= $queryWherePart;
        }

        if ($queryWhere != '') {
            $query .= ' WHERE ' . $queryWhere;
        }
        
        $query .= ' GROUP BY issue_main_table.id ';

        if ($securitySchemeUserId)
            $query .= ' HAVING ((security_check1 > 0 or security_check2 > 0 or security_check3 > 0 or security_check4 > 0 or security_check5 > 0 or security_check6 > 0 or security_check7 > 0) ' .
                        ' OR (issue_main_table.security_scheme_level_id is null and security_check1 is null and security_check2 is null and security_check3 is null and security_check4 is null and security_check5 is null and security_check6 is null and security_check7 is null)) ';

        if (isset($parameters['sort']) && isset($parameters['sort_order']) && $sortColumns) {
            for ($i = 0; $i < count($sortColumns); $i++) {
                $sortColumns[$i] = $sortColumns[$i] . ' ' . $parameters['sort_order'];
            }
        }

        if (count($sortColumns)) {
            $query .= 'ORDER BY ' . implode(', ', $sortColumns);
        }

        if (isset($parameters['page'])) {
            $query .= ' LIMIT ' . (($parameters['page'] - 1) * $parameters['issues_per_page']) . ', ' . ($parameters['issues_per_page']);
        }

//        $queryTest = $query;
//        for ($p = 0; $p < count($parameterArray); $p++) {
//            $queryTest = preg_replace('/\?/', $parameterArray[$p], $queryTest, 1);
//        }
//        echo $querytest;

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        if ($queryWhere != '') {
            $param_arr_ref = array();

            foreach ($parameterArray as $key => $value)
                $param_arr_ref[$key] = &$parameterArray[$key];

            if ($parameterType != '')
                call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterType), $param_arr_ref));
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result->num_rows) {
            return null;
        }

        if (isset($parameters['page'])) {
            $q = "SELECT FOUND_ROWS() as count;";
            $stmt = UbirimiContainer::get()['db.connection']->prepare($q);
            $stmt->execute();
            $result_total = $stmt->get_result();
            $count = $result_total->fetch_array(MYSQLI_ASSOC);

            return array($result, $count['count']);
        } else if ((isset($parameters['issue_id']) && !is_array($parameters['issue_id'])) || isset($parameters['nr'])) {

            $issueData = $result->fetch_array(MYSQLI_ASSOC);

            $issueData['component'] = array();
            $issueData['component_ids'] = array();
            $issueData['affects_version'] = array();
            $issueData['affects_version_ids'] = array();
            $issueData['fix_version'] = array();
            $issueData['fix_version_ids'] = array();

            $components = UbirimiContainer::get()['repository']->get(IssueComponent::class)->getByIssueIdAndProjectId($issueData['id'], $issueData['issue_project_id'], 'array');
            for ($i = 0; $i < count($components); $i++) {
                $issueData['component'][] = $components[$i]['name'];
                $issueData['component_ids'][] = $components[$i]['id'];
            }

            $affectsVersions = UbirimiContainer::get()['repository']->get(IssueVersion::class)->getByIssueIdAndProjectId($issueData['id'], $issueData['issue_project_id'], Issue::ISSUE_AFFECTED_VERSION_FLAG, 'array');
            for ($i = 0; $i < count($affectsVersions); $i++) {
                $issueData['affects_version'][] = $affectsVersions[$i]['name'];
                $issueData['affects_version_ids'][] = $affectsVersions[$i]['id'];
            }

            $fixVersions = UbirimiContainer::get()['repository']->get(IssueVersion::class)->getByIssueIdAndProjectId($issueData['id'], $issueData['issue_project_id'], Issue::ISSUE_FIX_VERSION_FLAG, 'array');
            for ($i = 0; $i < count($fixVersions); $i++) {
                $issueData['fix_version'][] = $fixVersions[$i]['name'];
                $issueData['fix_version_ids'][] = $fixVersions[$i]['id'];
            }

            return $issueData;
        } else {
            return $result;
        }
    }

    public function setUnassignedById($issueId) {
        $query = 'update yongo_issue SET user_assigned_id = null where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }

    public function get2DimensionalFilter($projectId, $resultType = 'array') {
        $query = 'select general_user.id, general_user.first_name, general_user.last_name, yongo_issue.status_id, COUNT(yongo_issue.status_id) AS count
                    FROM yongo_issue
                    LEFT join general_user on general_user.id = yongo_issue.user_assigned_id';

        if (-1 != $projectId) {
            $query .= ' WHERE yongo_issue.project_id = ?';
        }

        $query .= ' GROUP BY general_user.id, yongo_issue.status_id';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        if (-1 != $projectId) {
            $stmt->bind_param("i", $projectId);
        }

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
        } else {
            return null;
        }
    }

    public function updateField($issueId, $field_type, $newValue) {
        $query = 'update yongo_issue SET ' . $field_type . ' = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $newValue, $issueId);
        $stmt->execute();
    }

    public function updateResolution($projectIdArray, $oldResolutionId, $newResolutionId) {
        $projectSQL = implode(', ', $projectIdArray);
        $query_update = 'update yongo_issue SET resolution_id = ? where resolution_id = ? and project_id IN (' . $projectSQL . ')';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query_update);
        $stmt->bind_param("ii", $newResolutionId, $oldResolutionId);
        $stmt->execute();
    }

    public function updatePriority($projectIdArray, $oldPriorityId, $newPriorityId) {
        $projectSQL = implode(', ', $projectIdArray);
        $query_update = 'update yongo_issue SET priority_id = ? where priority_id = ? and project_id IN (' . $projectSQL . ')';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query_update);
        $stmt->bind_param("ii", $newPriorityId, $oldPriorityId);
        $stmt->execute();
    }

    public function updateType($projectIdArray, $oldTypeId, $newTypeId) {
        $projectSQL = implode(', ', $projectIdArray);
        $query_update = 'update yongo_issue SET type_id = ? where type_id = ? and project_id IN (' . $projectSQL . ')';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query_update);
        $stmt->bind_param("ii", $newTypeId, $oldTypeId);
        $stmt->execute();
    }

    public function addHistory($issueId, $userId, $field, $old_value, $new_value, $oldValueId, $newValueId, $now_date) {
        if (!$old_value) {
            $old_value = 'NULL';
        }

        if (!$new_value) {
            $new_value = 'NULL';
        }

        $query = "INSERT INTO issue_history(issue_id, by_user_id, field, old_value, new_value, old_value_id, new_value_id, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iissssss", $issueId, $userId, $field, $old_value, $new_value, $oldValueId, $newValueId, $now_date);
        $stmt->execute();
    }

    public function deleteById($issueId) {
        UbirimiContainer::get()['repository']->get(IssueComment::class)->deleteByIssueId($issueId);
        UbirimiContainer::get()['repository']->get(IssueHistory::class)->deleteByIssueId($issueId);
        UbirimiContainer::get()['repository']->get(IssueComponent::class)->deleteByIssueId($issueId);
        UbirimiContainer::get()['repository']->get(IssueVersion::class)->deleteByIssueId($issueId);

        UbirimiContainer::get()['repository']->get(Watcher::class)->deleteByIssueId($issueId);
        UbirimiContainer::get()['repository']->get(Issue::class)->deleteSLADataByIssueId($issueId);
        UbirimiContainer::get()['repository']->get(WorkLog::class)->deleteByIssueId($issueId);
        UbirimiContainer::get()['repository']->get(IssueAttachment::class)->deleteByIssueId($issueId);
        UbirimiContainer::get()['repository']->get(CustomField::class)->deleteCustomFieldsData($issueId);

        UbirimiContainer::get()['repository']->get(Board::class)->deleteIssuesFromSprints(array($issueId));

        $query = 'DELETE from yongo_issue WHERE id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueId);
        $stmt->execute();
    }

    public function addRaw($projectId, $date, $data) {
        $issueNumber = UbirimiContainer::get()['repository']->get(Issue::class)->getAvailableIssueNumber($projectId);

        $query = "INSERT INTO yongo_issue(project_id, priority_id, status_id, type_id, user_assigned_id, user_reported_id, nr, " .
            "summary, description, environment, date_created, date_due) " .
            "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iiiiiiisssss",
            $projectId,
            $data['priority'],
            $data['status'],
            $data['type'],
            $data['assignee'],
            $data['reporter'],
            $issueNumber,
            $data['summary'],
            $data['description'],
            $data['environment'],
            $date,
            $data['due_date']
        );

        $stmt->execute();

        return array(UbirimiContainer::get()['db.connection']->insert_id, $issueNumber);
    }

    public function add($project, $currentDate, $issueSystemFields, $loggedInUserId, $parentIssueId = null, $systemTimeTrackingDefaultUnit = null) {
        $issueNumber = UbirimiContainer::get()['repository']->get(Issue::class)->getAvailableIssueNumber($project['id']);
        $workflowUsed = UbirimiContainer::get()['repository']->get(YongoProject::class)->getWorkflowUsedForType($project['id'], $issueSystemFields['type']);

        $statusData = UbirimiContainer::get()['repository']->get(Workflow::class)->getDataForCreation($workflowUsed['id']);
        $StatusId = $statusData['linked_issue_status_id'];

        $query = "INSERT INTO yongo_issue(project_id, resolution_id, priority_id, status_id, type_id, user_assigned_id, user_reported_id, nr, " .
                                   "summary, description, environment, date_created, date_due, parent_id, security_scheme_level_id, " .
                                   "original_estimate, remaining_estimate, helpdesk_flag, user_reported_ip) " .
                 "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if (!array_key_exists('reporter', $issueSystemFields) || $issueSystemFields['reporter'] == null) {
            $issueSystemFields['reporter'] = $loggedInUserId;
        }

        $securityLevel = null;
        if (isset($issueSystemFields[Field::FIELD_ISSUE_SECURITY_LEVEL_CODE])) {
            $securityLevel = $issueSystemFields[Field::FIELD_ISSUE_SECURITY_LEVEL_CODE];
            if ($securityLevel == -1)
                $securityLevel = null;
        }

        $time_tracking_remaining_estimate = null;
        $time_tracking_original_estimate = null;

        if (!array_key_exists('helpdesk_flag', $issueSystemFields))
            $issueSystemFields['helpdesk_flag'] = 0;

        if (!array_key_exists('time_tracking_original_estimate', $issueSystemFields))
            $issueSystemFields['time_tracking_original_estimate'] = null;

        if (!array_key_exists('time_tracking_remaining_estimate', $issueSystemFields))
            $issueSystemFields['time_tracking_remaining_estimate'] = null;

        if ($issueSystemFields['time_tracking_original_estimate'] == null && $issueSystemFields['time_tracking_remaining_estimate'] != null)
            $issueSystemFields['time_tracking_original_estimate'] = $issueSystemFields['time_tracking_remaining_estimate'];
        else if ($issueSystemFields['time_tracking_remaining_estimate'] == null && $issueSystemFields['time_tracking_original_estimate'] != null)
            $issueSystemFields['time_tracking_remaining_estimate'] = $issueSystemFields['time_tracking_original_estimate'];

        $time_tracking_remaining_estimate = trim(str_replace(" ", '', $issueSystemFields['time_tracking_remaining_estimate']));
        $time_tracking_original_estimate = trim(str_replace(" ", '', $issueSystemFields['time_tracking_original_estimate']));

        if (is_numeric($time_tracking_remaining_estimate)) {
            $time_tracking_remaining_estimate .=  $systemTimeTrackingDefaultUnit;
        }

        if (is_numeric($time_tracking_original_estimate)) {
            $time_tracking_original_estimate .=  $systemTimeTrackingDefaultUnit;
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param(
            "iiiiiiiisssssiissis",
            $project['id'],
            $issueSystemFields['resolution'],
            $issueSystemFields['priority'],
            $StatusId,
            $issueSystemFields['type'],
            $issueSystemFields['assignee'],
            $issueSystemFields['reporter'],
            $issueNumber,
            $issueSystemFields['summary'],
            $issueSystemFields['description'],
            $issueSystemFields['environment'],
            $currentDate,
            $issueSystemFields['due_date'],
            $parentIssueId,
            $securityLevel,
            $time_tracking_remaining_estimate,
            $time_tracking_original_estimate,
            $issueSystemFields['helpdesk_flag'],
            $issueSystemFields['user_reported_ip']
        );

        $stmt->execute();

        return array(UbirimiContainer::get()['db.connection']->insert_id, $issueNumber);
    }

    public function getByIdSimple($issueId) {
        $query = 'SELECT * from yongo_issue where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->bind_param("i", $issueId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            $result = $result->fetch_array(MYSQLI_ASSOC);

            return $result;
        }

        return null;
    }

    public function getAvailableIssueNumber($projectId) {
        $query = 'SELECT issue_number ' .
                    'FROM project ' .
                    'WHERE id = ? ' .
                    'ORDER BY id desc ' .
                    'LIMIT 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            $result = $result->fetch_array(MYSQLI_ASSOC);
            $nr = $result['issue_number'];
            if (!$nr)
                $nr = 1;
            else
                $nr++;

            return $nr;
        }

        return 1;
    }

    public function addComponentVersion($issueId, $values, $table, $versionFlag = null) {
        $query = '';
        if ($table == 'issue_component')
            $query = "INSERT INTO issue_component(issue_id, project_component_id) VALUES ";
        else if ($table == 'issue_version')
            $query = "INSERT INTO issue_version(issue_id, project_version_id, affected_targeted_flag) VALUES ";
        $bind_param_str = '';
        $bind_param_arr = array();
        if (!is_array($values))
            $values = array($values);

        foreach ($values as $key => $value) {

            if (!$versionFlag) {
                $query .= '(?, ?), ';
                $bind_param_str .= 'ii';
            } else {
                $query .= '(?, ?, ?), ';
                $bind_param_str .= 'iii';
            }
            $bind_param_arr[] = $issueId;
            $bind_param_arr[] = (int)$value;
            if ($versionFlag) $bind_param_arr[] = $versionFlag;
        }

        $query = substr($query, 0, strlen($query) - 2);

        $bind_param_arr_ref = array();
        foreach ($bind_param_arr as $key => $value)
            $bind_param_arr_ref[$key] = &$bind_param_arr[$key];

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        call_user_func_array(array($stmt, "bind_param"), array_merge(array($bind_param_str), $bind_param_arr_ref));
        $stmt->execute();
    }

    public function updateById($issueId, $data, $updateDate) {

        $paramType = '';
        $paramValues = array();
        $query = 'update yongo_issue SET date_updated = ?,';
        $paramType .= 's';
        $paramValues[] = $updateDate;

        $time_tracking_remaining_estimate = null;
        $time_tracking_original_estimate = null;

        if ((array_key_exists('time_tracking_original_estimate', $data) && $data['time_tracking_original_estimate'] == null) &&
            (array_key_exists('time_tracking_remaining_estimate', $data) && $data['time_tracking_remaining_estimate'] != null)) {
            $data['time_tracking_original_estimate'] = $data['time_tracking_remaining_estimate'];
        } else if ((array_key_exists('time_tracking_remaining_estimate', $data) && $data['time_tracking_remaining_estimate'] == null) &&
            (array_key_exists('time_tracking_original_estimate', $data) && $data['time_tracking_original_estimate'] != null)) {
            $data['time_tracking_remaining_estimate'] = $data['time_tracking_original_estimate'];
        }

        if (array_key_exists('time_tracking_remaining_estimate', $data))
            $data['time_tracking_remaining_estimate'] = trim(str_replace(" ", '', $data['time_tracking_remaining_estimate']));
        if (array_key_exists('time_tracking_original_estimate', $data))
            $data['time_tracking_original_estimate'] = trim(str_replace(" ", '', $data['time_tracking_original_estimate']));

        foreach ($data as $field => $value) {

            switch ($field) {
                case 'time_tracking_remaining_estimate':
                    $query .= 'remaining_estimate = ?,';
                    $paramType .= 's';
                    $paramValues[] = $data['time_tracking_remaining_estimate'];

                    break;

                case 'time_tracking_original_estimate':
                    $query .= 'original_estimate = ?,';
                    $paramType .= 's';
                    $paramValues[] = $data['time_tracking_original_estimate'];
                    break;

                case Field::FIELD_ISSUE_TYPE_CODE:
                    $query .= 'type_id = ?,';
                    $paramType .= 'i';
                    $paramValues[] = $data['type'];
                    break;

                case Field::FIELD_PRIORITY_CODE:
                    $query .= 'priority_id = ?,';
                    $paramType .= 'i';
                    $paramValues[] = $data['priority'];
                    break;

                case Field::FIELD_SUMMARY_CODE:
                    $query .= 'summary = ?,';
                    $paramType .= 's';
                    $paramValues[] = $data['summary'];
                    break;

                case Field::FIELD_ISSUE_SECURITY_LEVEL_CODE:
                    $query .= 'security_scheme_level_id = ?,';
                    $paramType .= 'i';
                    if ($data[FIELD::FIELD_ISSUE_SECURITY_LEVEL_CODE] == -1)
                        $paramValues[] = null;
                    else
                        $paramValues[] = $data[FIELD::FIELD_ISSUE_SECURITY_LEVEL_CODE];
                    break;

                case Field::FIELD_DESCRIPTION_CODE:
                    $query .= 'description = ?,';
                    $paramType .= 's';
                    $paramValues[] = $data['description'];
                    break;

                case Field::FIELD_ENVIRONMENT_CODE:
                    $query .= 'environment = ?,';
                    $paramType .= 's';
                    $paramValues[] = $data['environment'];
                    break;

                case Field::FIELD_REPORTER_CODE:
                    $query .= 'user_reported_id = ?,';
                    $paramType .= 'i';
                    $paramValues[] = $data['reporter'];
                    break;

                case Field::FIELD_ASSIGNEE_CODE:
                    if ($data['assignee'] != -1) {
                        $query .= 'user_assigned_id = ?,';
                        $paramType .= 'i';
                        $paramValues[] = $data['assignee'];
                    } else {
                        $query .= 'user_assigned_id = NULL,';
                    }

                    break;

                case Field::FIELD_DUE_DATE_CODE:
                    $query .= 'date_due = ?,';
                    $paramType .= 's';
                    $paramValues[] = $data['due_date'];

                    break;

                case Field::FIELD_RESOLVED_DATE_CODE:
                    $query .= 'date_resolved = ?,';
                    $paramType .= 's';
                    $paramValues[] = $data['date_resolved'];

                    break;

                case Field::FIELD_RESOLUTION_CODE:
                    if ($data['resolution'] == null) {
                        $query .= 'resolution_id = NULL,';
                    } else {
                        $query .= 'resolution_id = ?,';
                        $paramType .= 'i';
                        $paramValues[] = $data['resolution'];
                    }

                    break;
            }
        }

        $query = substr($query, 0, strlen($query) - 1);
        $query .= ' where id = ? limit 1';
        $paramType .= 'i';
        $paramValues[] = $issueId;
        $bind_param_arr_ref = array();

        foreach ($paramValues as $key => $value)
            $bind_param_arr_ref[$key] = &$paramValues[$key];

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        call_user_func_array(array($stmt, "bind_param"), array_merge(array($paramType), $bind_param_arr_ref));
        $stmt->execute();

        if (array_key_exists(Field::FIELD_AFFECTS_VERSION_CODE, $data)) {
            UbirimiContainer::get()['repository']->get(IssueVersion::class)->deleteByIssueIdAndFlag($issueId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
            if ($data[Field::FIELD_AFFECTS_VERSION_CODE])
                UbirimiContainer::get()['repository']->get(Issue::class)->addComponentVersion($issueId, $data['affects_version'], 'issue_version', Issue::ISSUE_AFFECTED_VERSION_FLAG);
        }

        if (array_key_exists(Field::FIELD_FIX_VERSION_CODE, $data)) {
            UbirimiContainer::get()['repository']->get(IssueVersion::class)->deleteByIssueIdAndFlag($issueId, Issue::ISSUE_FIX_VERSION_FLAG);
            if ($data[Field::FIELD_FIX_VERSION_CODE])
                UbirimiContainer::get()['repository']->get(Issue::class)->addComponentVersion($issueId, $data['fix_version'], 'issue_version', Issue::ISSUE_FIX_VERSION_FLAG);
        }

        if (array_key_exists(Field::FIELD_COMPONENT_CODE, $data)) {
            UbirimiContainer::get()['repository']->get(IssueComponent::class)->deleteByIssueId($issueId);
            if ($data[Field::FIELD_COMPONENT_CODE])
                UbirimiContainer::get()['repository']->get(Issue::class)->addComponentVersion($issueId, $data['component'], 'issue_component');
        }
    }

    private function issueFieldChanged($fieldCode, $oldIssueData, $newIssueData) {

        $fieldChanged = false;

        if (isset($newIssueData[$fieldCode]) && $newIssueData[$fieldCode] != $oldIssueData[$fieldCode]) {
            $fieldChanged = true;
        }

        return $fieldChanged;
    }

    public function computeDifference($oldIssueData, $newIssueData, $oldIssueCustomFieldsData, $newIssueCustomFieldsData) {

        $fieldChanges = array();
        $issueId = $oldIssueData['id'];

        if (UbirimiContainer::get()['repository']->get(Issue::class)->issueFieldChanged(Field::FIELD_SUMMARY_CODE, $oldIssueData, $newIssueData)) {
            $fieldChanges[] = array(Field::FIELD_SUMMARY_CODE,
                                    $oldIssueData[Field::FIELD_SUMMARY_CODE],
                                    $newIssueData[Field::FIELD_SUMMARY_CODE]);
        }

        if (UbirimiContainer::get()['repository']->get(Issue::class)->issueFieldChanged(Field::FIELD_PROJECT_CODE, $oldIssueData, $newIssueData)) {
            $fieldChanges[] = array(Field::FIELD_PROJECT_CODE,
                                    $oldIssueData[Field::FIELD_PROJECT_CODE],
                                    $newIssueData[Field::FIELD_PROJECT_CODE],
                                    $oldIssueData['issue_project_id'],
                                    $newIssueData['issue_project_id']);
        }

        if (UbirimiContainer::get()['repository']->get(Issue::class)->issueFieldChanged(Field::FIELD_DESCRIPTION_CODE, $oldIssueData, $newIssueData)) {
            $fieldChanges[] = array(Field::FIELD_DESCRIPTION_CODE,
                                    $oldIssueData[Field::FIELD_DESCRIPTION_CODE],
                                    $newIssueData[Field::FIELD_DESCRIPTION_CODE]);
        }
        if (UbirimiContainer::get()['repository']->get(Issue::class)->issueFieldChanged(Field::FIELD_ENVIRONMENT_CODE, $oldIssueData, $newIssueData)) {
            $fieldChanges[] = array(Field::FIELD_ENVIRONMENT_CODE,
                                    $oldIssueData[Field::FIELD_ENVIRONMENT_CODE],
                                    $newIssueData[Field::FIELD_ENVIRONMENT_CODE]);
        }

        if (UbirimiContainer::get()['repository']->get(Issue::class)->issueFieldChanged(Field::FIELD_DUE_DATE_CODE, $oldIssueData, $newIssueData)) {
            $fieldChanges[] = array(Field::FIELD_DUE_DATE_CODE,
                                    $oldIssueData[Field::FIELD_DUE_DATE_CODE],
                                    $newIssueData[Field::FIELD_DUE_DATE_CODE]);
        }

        if (UbirimiContainer::get()['repository']->get(Issue::class)->issueFieldChanged(Field::FIELD_ISSUE_TYPE_CODE, $oldIssueData, $newIssueData)) {
            $fieldChangedOldValueRow = UbirimiContainer::get()['repository']->get(IssueType::class)->getById($oldIssueData[Field::FIELD_ISSUE_TYPE_CODE]);
            $fieldChangedNewValueRow = UbirimiContainer::get()['repository']->get(IssueType::class)->getById($newIssueData[Field::FIELD_ISSUE_TYPE_CODE]);
            $fieldChanges[] = array(Field::FIELD_ISSUE_TYPE_CODE,
                                    $fieldChangedOldValueRow['name'],
                                    $fieldChangedNewValueRow['name'],
                                    $oldIssueData[Field::FIELD_ISSUE_TYPE_CODE],
                                    $newIssueData[Field::FIELD_ISSUE_TYPE_CODE]);
        }

        if (UbirimiContainer::get()['repository']->get(Issue::class)->issueFieldChanged(Field::FIELD_PRIORITY_CODE, $oldIssueData, $newIssueData)) {
            $fieldChangedOldValueRow = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($oldIssueData[Field::FIELD_PRIORITY_CODE], 'priority');
            $fieldChangedNewValueRow = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($newIssueData[Field::FIELD_PRIORITY_CODE], 'priority');

            $fieldChanges[] = array(Field::FIELD_PRIORITY_CODE,
                                    $fieldChangedOldValueRow['name'],
                                    $fieldChangedNewValueRow['name'],
                                    $oldIssueData[Field::FIELD_PRIORITY_CODE],
                                    $newIssueData[Field::FIELD_PRIORITY_CODE]);
        }

        if (UbirimiContainer::get()['repository']->get(Issue::class)->issueFieldChanged(Field::FIELD_STATUS_CODE, $oldIssueData, $newIssueData)) {
            $fieldChangedOldValueRow = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($oldIssueData[Field::FIELD_STATUS_CODE], 'status');
            $fieldChangedNewValueRow = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($newIssueData[Field::FIELD_STATUS_CODE], 'status');
            $fieldChanges[] = array(Field::FIELD_STATUS_CODE,
                                    $fieldChangedOldValueRow['name'],
                                    $fieldChangedNewValueRow['name'],
                                    $oldIssueData[Field::FIELD_STATUS_CODE],
                                    $newIssueData[Field::FIELD_STATUS_CODE]);
        }

        if (UbirimiContainer::get()['repository']->get(Issue::class)->issueFieldChanged(Field::FIELD_RESOLUTION_CODE, $oldIssueData, $newIssueData)) {
            $fieldChangedOldValueRow = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($oldIssueData[Field::FIELD_RESOLUTION_CODE], 'resolution');
            $fieldChangedNewValueRow = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getById($newIssueData[Field::FIELD_RESOLUTION_CODE], 'resolution');

            $fieldChanges[] = array(Field::FIELD_RESOLUTION_CODE,
                                    $fieldChangedOldValueRow['name'],
                                    $fieldChangedNewValueRow['name'],
                                    $oldIssueData[Field::FIELD_RESOLUTION_CODE],
                                    $newIssueData[Field::FIELD_RESOLUTION_CODE]);
        }

        if (UbirimiContainer::get()['repository']->get(Issue::class)->issueFieldChanged(Field::FIELD_ASSIGNEE_CODE, $oldIssueData, $newIssueData)) {
            $fieldChangedOldValueRow = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getById($oldIssueData[Field::FIELD_ASSIGNEE_CODE]);
            $fieldChangedOldValue = $fieldChangedOldValueRow['first_name'] . ' ' . $fieldChangedOldValueRow['last_name'];

            $fieldChangedNewValueRow = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getById($newIssueData[Field::FIELD_ASSIGNEE_CODE]);
            $fieldChangedNewValue = $fieldChangedNewValueRow['first_name'] . ' ' . $fieldChangedNewValueRow['last_name'];
            $fieldChanges[] = array(Field::FIELD_ASSIGNEE_CODE,
                                    $fieldChangedOldValue,
                                    $fieldChangedNewValue,
                                    $oldIssueData[Field::FIELD_ASSIGNEE_CODE],
                                    $newIssueData[Field::FIELD_ASSIGNEE_CODE]);
        }

        if (UbirimiContainer::get()['repository']->get(Issue::class)->issueFieldChanged(Field::FIELD_REPORTER_CODE, $oldIssueData, $newIssueData)) {
            $fieldChangedOldValueRow = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getById($oldIssueData[Field::FIELD_REPORTER_CODE]);
            $fieldChangedOldValue = $fieldChangedOldValueRow['first_name'] . ' ' . $fieldChangedOldValueRow['last_name'];
            $fieldChangedNewValueRow = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getById($newIssueData[Field::FIELD_REPORTER_CODE]);
            $fieldChangedNewValue = $fieldChangedNewValueRow['first_name'] . ' ' . $fieldChangedNewValueRow['last_name'];
            $fieldChanges[] = array(Field::FIELD_REPORTER_CODE,
                                    $fieldChangedOldValue,
                                    $fieldChangedNewValue,
                                    $oldIssueData[Field::FIELD_REPORTER_CODE],
                                    $newIssueData[Field::FIELD_REPORTER_CODE]);
        }

        $newIssueData['component'] = array();
        $newIssueData['component_ids'] = array();
        $newIssueData['affects_version'] = array();
        $newIssueData['affects_version_ids'] = array();
        $newIssueData['fix_version'] = array();
        $newIssueData['fix_version_ids'] = array();

        $components = UbirimiContainer::get()['repository']->get(IssueComponent::class)->getByIssueIdAndProjectId($issueId, $newIssueData['issue_project_id'], 'array');
        for ($i = 0; $i < count($components); $i++) {
            $newIssueData['component'][] = $components[$i]['name'];
            $newIssueData['component_ids'][] = $components[$i]['id'];
        }

        $affectsVersions = UbirimiContainer::get()['repository']->get(IssueVersion::class)->getByIssueIdAndProjectId($issueId, $newIssueData['issue_project_id'], Issue::ISSUE_AFFECTED_VERSION_FLAG, 'array');
        for ($i = 0; $i < count($affectsVersions); $i++) {
            $newIssueData['affects_version'][] = $affectsVersions[$i]['name'];
            $newIssueData['affects_version_ids'][] = $affectsVersions[$i]['id'];
        }

        $fixVersions = UbirimiContainer::get()['repository']->get(IssueVersion::class)->getByIssueIdAndProjectId($issueId, $newIssueData['issue_project_id'], Issue::ISSUE_FIX_VERSION_FLAG, 'array');
        for ($i = 0; $i < count($fixVersions); $i++) {
            $newIssueData['fix_version'][] = $fixVersions[$i]['name'];
            $newIssueData['fix_version_ids'][] = $fixVersions[$i]['id'];
        }

        if ($oldIssueData['component'] != $newIssueData['component']) {
            $fieldChanges[] = array(Field::FIELD_COMPONENT_CODE,
                                    implode(', ', $oldIssueData['component']),
                                    implode(', ', $newIssueData['component']),
                                    implode(', ', $oldIssueData['component_ids']),
                                    implode(', ', $newIssueData['component_ids']));
        }

        if ($oldIssueData['fix_version'] != $newIssueData['fix_version']) {
            $fieldChanges[] = array(Field::FIELD_FIX_VERSION_CODE,
                                    implode(', ', $oldIssueData['fix_version']),
                                    implode(', ', $newIssueData['fix_version']),
                                    implode(', ', $oldIssueData['fix_version_ids']),
                                    implode(', ', $newIssueData['fix_version_ids']));
        }

        if ($oldIssueData['affects_version'] != $newIssueData['affects_version']) {
            $fieldChanges[] = array(Field::FIELD_AFFECTS_VERSION_CODE,
                                    implode(', ', $oldIssueData['affects_version']),
                                    implode(', ', $newIssueData['affects_version']),
                                    implode(', ', $oldIssueData['affects_version_ids']),
                                    implode(', ', $newIssueData['affects_version_ids']));
        }

        // deal with custom field values also
        foreach ($newIssueCustomFieldsData as $key => $value) {
            $fieldData = UbirimiContainer::get()['repository']->get(Field::class)->getById($key);

            $oldCustomFieldValue = UbirimiContainer::get()['repository']->get(CustomField::class)->getCustomFieldsDataByFieldId($issueId, $key);
            if ($oldCustomFieldValue) {
                switch ($fieldData['sys_field_type_id']) {
                    case Field::CUSTOM_FIELD_TYPE_SMALL_TEXT_CODE_ID;
                    case Field::CUSTOM_FIELD_TYPE_DATE_PICKER_CODE_ID:
                    case Field::CUSTOM_FIELD_TYPE_DATE_TIME_PICKER_CODE_ID:
                    case Field::CUSTOM_FIELD_TYPE_BIG_TEXT_CODE_ID:
                    case Field::CUSTOM_FIELD_TYPE_NUMBER_CODE_ID:
                    case Field::CUSTOM_FIELD_TYPE_SELECT_LIST_SINGLE_CODE_ID:

                        $valueData = $oldCustomFieldValue->fetch_array(MYSQLI_ASSOC);
                        $oldIssueCustomFieldsData[$key] = $valueData['value'];

                        break;

                    case Field::CUSTOM_FIELD_TYPE_USER_PICKER_MULTIPLE_USER_CODE_ID:
                        $valueField = array();
                        while ($data = $oldCustomFieldValue->fetch_array(MYSQLI_ASSOC)) {
                            $valueField[] = $data['value'];
                        }
                        $oldIssueCustomFieldsData[$key] = $valueField;

                        break;
                }
            }
        }

        foreach ($newIssueCustomFieldsData as $key => $value) {
            $fieldData = UbirimiContainer::get()['repository']->get(Field::class)->getById($key);
            $fieldTypeId = $fieldData['sys_field_type_id'];
            $fieldName = $fieldData['name'];

            if (!array_key_exists($key, $oldIssueCustomFieldsData)) {
                $oldIssueCustomFieldsData[$key] = null;
            }

            if (is_array($value)) {
                switch ($fieldTypeId) {
                    case Field::CUSTOM_FIELD_TYPE_USER_PICKER_MULTIPLE_USER_CODE_ID:
                        $oldUsers = $oldIssueCustomFieldsData[$key];
                        $newUsers = $newIssueCustomFieldsData[$key];

                        if ($oldUsers == null) {
                            $oldUsers = array();
                        }

                        $oldUsersDeleted = array_diff($oldUsers, $newUsers);
                        $newUsersAdded = array_diff($newUsers, $oldUsers);

                        // push only if $oldUsersDeleted != $newUsersAdded
                        if (array_diff($oldUsersDeleted, $newUsersAdded) !== array_diff($newUsersAdded, $oldUsersDeleted)) {
                            $oldUsersArray = array();
                            if (count($oldUsersDeleted)) {
                                $oldUsersData = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getByIds($oldUsersDeleted, 'array');
                                $oldUsersArray = array();
                                for ($i = 0; $i < count($oldUsersData); $i++) {
                                    $oldUsersArray[] = $oldUsersData[$i]['first_name'] . ' ' . $oldUsersData[$i]['last_name'];
                                }
                            }
                            $newUsersArray = array();
                            if (count($newUsersAdded)) {
                                $newUsersData = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getByIds($newUsersAdded, 'array');
                                for ($i = 0; $i < count($newUsersData); $i++) {
                                    $newUsersArray[] = $newUsersData[$i]['first_name'] . ' ' . $newUsersData[$i]['last_name'];
                                }
                            }

                            $fieldChanges[] = array($fieldName, implode(', ', $oldUsersArray), implode(', ', $newUsersArray), null, null, $fieldTypeId);
                        }

                        break;
                }
            } else {
                if ($newIssueCustomFieldsData[$key] != $oldIssueCustomFieldsData[$key]) {
                    $fieldChanges[] = array($fieldName, $oldIssueCustomFieldsData[$key], $newIssueCustomFieldsData[$key], null, null, $fieldTypeId);
                }
            }
        }

        return $fieldChanges;
    }

    public function updateHistory($issueId, $loggedInUserId, $fieldChanges, $currentDate) {

        for ($i = 0; $i < count($fieldChanges); $i++) {
            if ($fieldChanges[$i][0] != 'comment') {
                if ($fieldChanges[$i][1] != $fieldChanges[$i][2]) {

                    $oldIds = null;
                    $newIds = null;
                    if (isset($fieldChanges[$i][3])) {
                        $oldIds = $fieldChanges[$i][3];
                    }
                    if (isset($fieldChanges[$i][4])) {
                        $newIds = $fieldChanges[$i][4];
                    }

                    UbirimiContainer::get()['repository']->get(Issue::class)->addHistory($issueId,
                                      $loggedInUserId,
                                      $fieldChanges[$i][0],
                                      $fieldChanges[$i][1],
                                      $fieldChanges[$i][2],
                                      $oldIds,
                                      $newIds,
                                      $currentDate);
                }
            }
        }
    }

    public function getAll($filters = array()) {
        $query = 'select * from yongo_issue ' .
                 'where 1 = 1';

        if (!empty($filters['today'])) {
            $query .= " and DATE(date_created) = DATE(NOW())";
        }

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows)
            return $result;
        else
            return false;
    }

    public function updateSecurityLevel($clientId, $issueSecuritySchemeLevelId, $newIssueSecuritySchemeLevelId) {
        $query = 'select yongo_issue.id ' .
            'from yongo_issue ' .
            'left join project on project.id = yongo_issue.project_id ' .
            'where project.client_id = ? and ' .
            'yongo_issue.security_scheme_level_id = ?';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $clientId, $issueSecuritySchemeLevelId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows) {
            if ($newIssueSecuritySchemeLevelId == -1)
                $newIssueSecuritySchemeLevelId = null;

            while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                $queryUpdate = 'update yongo_issue set security_scheme_level_id = ? where id = ? limit 1';

                if ($stmtUpdate = UbirimiContainer::get()['db.connection']->prepare($queryUpdate)) {

                    $stmtUpdate->bind_param("ii", $newIssueSecuritySchemeLevelId, $data['id']);
                    $stmtUpdate->execute();
                }
            }
        }
    }

    public function setAffectedVersion($issueId, $projectVersionId) {
        $query = "INSERT INTO issue_version(issue_id, project_version_id, affected_targeted_flag) VALUES (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);

        $versionType = self::ISSUE_AFFECTED_VERSION_FLAG;
        $stmt->bind_param("iii", $issueId, $projectVersionId, $versionType);
        $stmt->execute();
    }

    public function updateAssignee($clientId, $issueId, $loggedInUserId, $userAssignedId, $comment = null) {
        $issueData = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters(array('issue_id' => $issueId), $loggedInUserId);

        if ($userAssignedId != -1) {
            UbirimiContainer::get()['repository']->get(Issue::class)->updateField($issueId, 'user_assigned_id', $userAssignedId);
        } else {
            UbirimiContainer::get()['repository']->get(Issue::class)->setUnassignedById($issueId);
        }

        $oldAssignee = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getById($issueData['assignee']);
        $newAssignee = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getById($userAssignedId);

        $oldAssigneeName = $oldAssignee['first_name'] . ' ' . $oldAssignee['last_name'];
        $newAssigneeName = $newAssignee['first_name'] . ' ' . $newAssignee['last_name'];

        $date = Util::getServerCurrentDateTime();

        UbirimiContainer::get()['repository']->get(Issue::class)->addHistory($issueId, $loggedInUserId, Field::FIELD_ASSIGNEE_CODE, $oldAssigneeName, $newAssigneeName, $oldAssignee['id'], $newAssignee['id'], $date);

        if (!empty($comment)) {
            UbirimiContainer::get()['repository']->get(IssueComment::class)->add($issueId, $loggedInUserId, $comment, $date);
        }
    }

    public function move($issueId, $newProjectId, $newIssueTypeId, $newSubTaskIssueTypeIds) {

        $nextNumber = UbirimiContainer::get()['repository']->get(Issue::class)->getAvailableIssueNumber($newProjectId);
        $query = 'update yongo_issue SET project_id = ?, type_id = ?, nr = ? where id = ? limit 1';

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iisi", $newProjectId, $newIssueTypeId, $nextNumber, $issueId);
        $stmt->execute();
        $stmt->close();

        // update last issue number for this project
        UbirimiContainer::get()['repository']->get(YongoProject::class)->updateLastIssueNumber($newProjectId, $nextNumber);

        $subTasks = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters(array('parent_id' => $issueId));
        if ($subTasks) {
            while ($issue = $subTasks->fetch_array(MYSQLI_ASSOC)) {
                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $nextNumber = UbirimiContainer::get()['repository']->get(Issue::class)->getAvailableIssueNumber($newProjectId);

                $subTaskId = $issue['id'];
                $stmt->bind_param("iisi", $newProjectId, $newIssueTypeId, $nextNumber, $subTaskId);
                $stmt->execute();

                // update last issue number for this project
                UbirimiContainer::get()['repository']->get(YongoProject::class)->updateLastIssueNumber($newProjectId, $nextNumber);

                UbirimiContainer::get()['repository']->get(IssueVersion::class)->deleteByIssueIdAndFlag($subTaskId, Issue::ISSUE_FIX_VERSION_FLAG);
                UbirimiContainer::get()['repository']->get(IssueVersion::class)->deleteByIssueIdAndFlag($subTaskId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
                UbirimiContainer::get()['repository']->get(IssueComponent::class)->deleteByIssueId($subTaskId);

                // also update the issue type Id if necessary
                for ($i = 0; $i < count($newSubTaskIssueTypeIds); $i++) {
                    if ($issue['type'] = $newSubTaskIssueTypeIds[$i][0]) {
                        $newTypeId = $newSubTaskIssueTypeIds[$i][1];

                        $queryUpdateType = 'update yongo_issue SET type_id = ? where id = ? limit 1';

                        if ($stmtUpdateType = UbirimiContainer::get()['db.connection']->prepare($queryUpdateType)) {
                            $stmtUpdateType->bind_param("ii", $newTypeId, $subTaskId);
                            $stmtUpdateType->execute();
                        }
                    }
                }
            }
        }
    }

    public function prepareDataForSearchFromURL($data, $issuesPerPage) {
        $getFilter = isset($data['filter']) ? $data['filter'] : null;
        $getForQueue = isset($data['for_queue']) ? $data['for_queue'] : null;
        $getPage = isset($data['page']) ? $data['page'] : 1;
        $getSortColumn = isset($data['sort']) ? $data['sort'] : 'created';
        $getSortOrder = isset($data['order']) ? $data['order'] : 'desc';
        $getSearchQuery = isset($data['search_query']) ? $data['search_query'] : null;
        $getSummaryFlag = isset($data['summary_flag']) ? $data['summary_flag'] : null;
        $getDescriptionFlag = isset($data['description_flag']) ? $data['description_flag'] : null;
        $getCommentsFlag = isset($data['comments_flag']) ? $data['comments_flag'] : null;
        $getProjectIds = isset($data['project']) ? explode('|', $data['project']) : null;

        $getAssigneeIds = isset($data['assignee']) ? explode('|', $data['assignee']) : null;
        $getReportedIds = isset($data['reporter']) ? explode('|', $data['reporter']) : null;
        $getIssueTypeIds = isset($data['type']) ? explode('|', $data['type']) : null;
        $getIssueStatusIds = isset($data['status']) ? explode('|', $data['status']) : null;
        $getIssuePriorityIds = isset($data['priority']) ? explode('|', $data['priority']) : null;
        $getProjectComponentIds = isset($data['component']) ? explode('|', $data['component']) : null;
        $getProjectFixVersionIds = isset($data['fix_version']) ? explode('|', $data['fix_version']) : null;
        $getProjectAffectsVersionIds = isset($data['affects_version']) ? explode('|', $data['affects_version']) : null;
        $getIssueResolutionIds = isset($data['resolution']) ? explode('|', $data['resolution']) : null;

        // date filters
        $getDateDueAfter = isset($data['date_due_after']) ? $data['date_due_after'] : null;
        $getDateDueBefore = isset($data['date_due_before']) ? $data['date_due_before'] : null;

        $getDateCreatedAfter = isset($data['date_created_after']) ? $data['date_created_after'] : null;
        $getDateCreatedBefore = isset($data['date_created_before']) ? $data['date_created_before'] : null;

        $getSearchParameters = array('search_query' => $getSearchQuery, 'summary_flag' => $getSummaryFlag, 'description_flag' => $getDescriptionFlag, 'comments_flag' => $getCommentsFlag,
            'project' => $getProjectIds, 'assignee' => $getAssigneeIds, 'reporter' => $getReportedIds, 'filter' => $getFilter,
            'type' => $getIssueTypeIds, 'status' => $getIssueStatusIds, 'priority' => $getIssuePriorityIds, 'component' => $getProjectComponentIds, 'fix_version' => $getProjectFixVersionIds,
            'resolution' => $getIssueResolutionIds, 'sort' => $getSortColumn, 'sort_order' => $getSortOrder, 'page' => $getPage, 'issues_per_page' => $issuesPerPage,
            'date_due_after' => $getDateDueAfter, 'date_due_before' => $getDateDueBefore, 'date_created_after' => $getDateCreatedAfter, 'date_created_before' => $getDateCreatedBefore,
            'affects_version' => $getProjectAffectsVersionIds, 'for_queue' => $getForQueue);

        return $getSearchParameters;
    }

    public function prepareWhereClauseFromQueue($queueDefinition, $userId, $projectId, $clientId) {

        $value = mb_strtolower($queueDefinition);
        $SLAs = UbirimiContainer::get()['repository']->get(Sla::class)->getByProjectId($projectId, 'array', "LENGTH('name') DESC");

        foreach ($SLAs as $SLA) {
            if (stripos($value, mb_strtolower($SLA['name'])) !== false) {
                $slaId = $SLA['id'];

                $sqlQueryPart = '(select value from yongo_issue_sla where yongo_issue_id = issue_main_table.id and help_sla_id = ' . $slaId . ' limit 1) is not null and ';
                $sqlQueryPart .= '(select value from yongo_issue_sla where yongo_issue_id = issue_main_table.id and help_sla_id = ' . $slaId . ' limit 1)';
                $value = str_ireplace(mb_strtolower($SLA['name']), $sqlQueryPart, $value);
            }
        }
        $value = str_ireplace('assignee', 'issue_main_table.user_assigned_id', $value);

        $value = str_ireplace('currentUser()', $userId, $value);
        $value = str_ireplace('type', 'issue_main_table.type_id', $value);
        $value = str_ireplace('status', 'issue_main_table.status_id', $value);
        $value = str_ireplace('resolution', 'issue_main_table.resolution_id', $value);
        $value = str_ireplace('= unresolved', 'IS NULL', $value);

        $statuses = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('status', $clientId);
        $priorities = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('priority', $clientId);
        $resolutions = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('resolution', $clientId);
        $types = UbirimiContainer::get()['repository']->get(IssueType::class)->getAll($clientId);

        while ($statuses && $status = $statuses->fetch_array(MYSQLI_ASSOC)) {
            $value = str_ireplace(mb_strtolower($status['name']), $status['id'], $value);
        }

        while ($priorities && $priority = $priorities->fetch_array(MYSQLI_ASSOC)) {
            $value = str_ireplace(mb_strtolower($priority['name']), $priority['id'], $value);
        }

        while ($resolutions && $resolution = $resolutions->fetch_array(MYSQLI_ASSOC)) {
            $value = str_ireplace(mb_strtolower($resolution['name']), $resolution['id'], $value);
        }

        while ($types && $type = $types->fetch_array(MYSQLI_ASSOC)) {
            $value = str_ireplace(mb_strtolower($type['name']), $type['id'], $value);
        }

        return $value;
    }

    public function addSLAData($issueId, $slaId, $offset) {
        $query = "insert into yongo_issue_sla(yongo_issue_id, help_sla_id, `value`) values (?, ?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $issueId, $slaId, $offset);
        $stmt->execute();
    }

    public function updateSLADataForProject($clientId, $projectId, $userId, $clientSettings) {
        $SLAs = UbirimiContainer::get()['repository']->get(Sla::class)->getByProjectId($projectId);

        if ($SLAs) {

            $issueQueryParameters = array('project' => $projectId);
            $issues = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters($issueQueryParameters, $userId);

            // check issue against the slas
            while ($SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {

                while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                    $slaData = UbirimiContainer::get()['repository']->get(Sla::class)->getOffsetForIssue($SLA, $issue, $clientId, $clientSettings);

                    if ($slaData[0]) {
                        UbirimiContainer::get()['repository']->get(Issue::class)->updateSLAValueOnly($issue['id'], $SLA['id'], $slaData[0]);
                    }
                }
                if ($issues) {
                    $issues->data_seek(0);
                }
            }
        }
    }

    public function updateSLAValue($issue, $clientId, $clientSettings) {
        $slasPrintData = array();
        $projectId = $issue['issue_project_id'];
        $SLAs = UbirimiContainer::get()['repository']->get(Sla::class)->getByProjectId($projectId);

        if ($SLAs) {
            // check issue against the SLAs
            while ($SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {

                $slaData = UbirimiContainer::get()['repository']->get(Sla::class)->getOffsetForIssue($SLA, $issue, $clientId, $clientSettings);
                if ($slaData) {
                    $slasPrintData[] = $slaData;
                    UbirimiContainer::get()['repository']->get(Sla::class)->updateDataForSLA($issue['id'], $slaData['slaId'], $slaData['intervalMinutes'], $slaData['goalId'], $slaData['startDate'], $slaData['endDate']);
                }
            }
        }

        return $slasPrintData;
    }

    public function addPlainSLAData($issueId, $projectId) {
        $SLAs = UbirimiContainer::get()['repository']->get(Sla::class)->getByProjectId($projectId);
        if ($SLAs) {
            while ($SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {
                $query = "INSERT INTO yongo_issue_sla(yongo_issue_id, help_sla_id) VALUES (?, ?)";

                $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
                $stmt->bind_param("ii", $issueId, $SLA['id']);
                $stmt->execute();
            }
        }
    }

    public function addPlainSLADataBySLAId($issueId, $SLAId) {
        $query = "INSERT INTO yongo_issue_sla(yongo_issue_id, help_sla_id) VALUES (?, ?)";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueId, $SLAId);
        $stmt->execute();
    }

    public function clearSLAData($slaId) {
        $query = "update yongo_issue_sla set help_sla_goal_id = NULL, started_flag = 0, stopped_flag = 0, started_date = NULL, value = NULL where help_sla_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $slaId);
        $stmt->execute();
    }

    public function getSearchParameters($projectsForBrowsing, $clientId, $helpDeskFlag = 0) {
        $projectsForBrowsing->data_seek(0);
        $projectIds = Util::getAsArray($projectsForBrowsing, array('id'));

        $allClientIssueTypes = UbirimiContainer::get()['repository']->get(IssueType::class)->getByProjects($projectIds);
        $criteria = array();
        $issueTypeArray = array();
        while ($allClientIssueTypes && $issueType = $allClientIssueTypes->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($issueTypeArray); $i++) {
                if ($issueTypeArray[$i]['name'] == $issueType['name']) {
                    $issueTypeArray[$i]['id'] .= '#' . $issueType['id'];
                    $found = true;
                    break;
                }
            }
            if (!$found)
                $issueTypeArray[] = $issueType;
        }
        $criteria['all_client_issue_type'] = $issueTypeArray;

        $allClientIssueStatuses = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('status', $clientId);
        $issueStatusArray = array();
        while ($issueStatus = $allClientIssueStatuses->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($issueStatusArray); $i++) {
                if ($issueStatusArray[$i]['name'] == $issueStatus['name']) {
                    $issueStatusArray[$i]['id'] .= '#' . $issueStatus['id'];
                    $found = true;
                    break;
                }
            }
            if (!$found)
                $issueStatusArray[] = $issueStatus;
        }
        $criteria['all_client_issue_status'] = $issueStatusArray;

        $allClientIssuePriorities = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('priority', $clientId);

        $issuePriorityArray = array();
        while ($issuePriority = $allClientIssuePriorities->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($issuePriorityArray); $i++) {
                if ($issuePriorityArray[$i]['name'] == $issuePriority['name']) {
                    $issuePriorityArray[$i]['id'] .= '#' . $issuePriority['id'];
                    $found = true;
                    break;
                }
            }
            if (!$found)
                $issuePriorityArray[] = $issuePriority;
        }
        $criteria['all_client_issue_priority'] = $issuePriorityArray;

        $allClientIssueResolutions = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('resolution', $clientId);
        $issueResolutionArray = array();
        while ($issueResolution = $allClientIssueResolutions->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($issueResolutionArray); $i++) {
                if ($issueResolutionArray[$i]['name'] == $issueResolution['name']) {
                    $issueResolutionArray[$i]['id'] .= '#' . $issueResolution['id'];
                    $found = true;
                    break;
                }
            }
            if (!$found)
                $issueResolutionArray[] = $issueResolution;
        }
        $criteria['all_client_issue_resolution'] = $issueResolutionArray;

        $clientUsersArray = array();

        if ($helpDeskFlag) {
            $allClientUsers = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getByClientId($clientId);
            while ($allClientUsers && $clientUser = $allClientUsers->fetch_array(MYSQLI_ASSOC)) {
                $found = false;
                for ($i = 0; $i < count($clientUsersArray); $i++) {
                    if ($clientUsersArray[$i]['first_name'] == $clientUser['first_name'] && $clientUsersArray[$i]['last_name'] == $clientUser['last_name']) {
                        $clientUsersArray[$i]['id'] .= '#' . $clientUser['id'];
                        $found = true;
                        break;
                    }
                }
                if (!$found)
                    $clientUsersArray[] = $clientUser;
            }
            $criteria['all_client_user_assignee'] = $clientUsersArray;

            $allClientUsers = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getByClientId($clientId, 1);
            $clientUsersArray = array();
            while ($allClientUsers && $clientUser = $allClientUsers->fetch_array(MYSQLI_ASSOC)) {
                $found = false;
                for ($i = 0; $i < count($clientUsersArray); $i++) {
                    if ($clientUsersArray[$i]['first_name'] == $clientUser['first_name'] && $clientUsersArray[$i]['last_name'] == $clientUser['last_name']) {
                        $clientUsersArray[$i]['id'] .= '#' . $clientUser['id'];
                        $found = true;
                        break;
                    }
                }
                if (!$found)
                    $clientUsersArray[] = $clientUser;
            }
            $criteria['all_client_user_reporter'] = $clientUsersArray;

        } else {
            $allClientUsers = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getByClientId($clientId);
            while ($allClientUsers && $clientUser = $allClientUsers->fetch_array(MYSQLI_ASSOC)) {
                $found = false;
                for ($i = 0; $i < count($clientUsersArray); $i++) {
                    if ($clientUsersArray[$i]['first_name'] == $clientUser['first_name'] && $clientUsersArray[$i]['last_name'] == $clientUser['last_name']) {
                        $clientUsersArray[$i]['id'] .= '#' . $clientUser['id'];
                        $found = true;
                        break;
                    }
                }
                if (!$found)
                    $clientUsersArray[] = $clientUser;
            }
            $criteria['all_client_user_assignee'] = $clientUsersArray;
            $criteria['all_client_user_reporter'] = $clientUsersArray;
        }

        // get the project components
        $allProjectsComponents = UbirimiContainer::get()['repository']->get(YongoProject::class)->getComponents($projectIds);
        $projectComponentsArray = array();
        while ($allProjectsComponents && $component = $allProjectsComponents->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($projectComponentsArray); $i++) {
                if ($projectComponentsArray[$i]['name'] == $component['name']) {
                    $projectComponentsArray[$i]['id'] .= '#' . $component['id'];
                    $found = true;
                    break;
                }
            }
            if (!$found)
                $projectComponentsArray[] = $component;
        }
        $criteria['all_client_issue_component'] = $projectComponentsArray;

        // get the project fix versions
        $allProjectsVersions = UbirimiContainer::get()['repository']->get(YongoProject::class)->getVersions($projectIds);
        $projectVersionsArray = array();
        while ($allProjectsVersions && $version = $allProjectsVersions->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($projectVersionsArray); $i++) {
                if ($projectVersionsArray[$i]['name'] == $version['name']) {
                    $projectVersionsArray[$i]['id'] .= '#' . $version['id'];
                    $found = true;
                    break;
                }
            }
            if (!$found)
                $projectVersionsArray[] = $version;
        }
        $criteria['all_client_issue_version'] = $projectVersionsArray;

        return $criteria;
    }
    
    public function prepareDataForSearchFromPostGet($projectIds, $postArray, $getArray) {
        $getFilter = isset($getArray['filter']) ? $getArray['filter'] : null;
        $searchText = $postArray['query'];
        $summaryFlag = isset($postArray['summary_flag']) ? 1 : 0;
        $descriptionFlag = isset($postArray['description_flag']) ? 1 : 0;
        $commentsFlag = isset($postArray['comments_flag']) ? 1 : 0;

        $selectedProjectArray = $postArray['search_project_list'];
        if (count($selectedProjectArray) == 1 && $selectedProjectArray[0] == -1) {
            $selectedProjectArray = $projectIds;
        }
        $selectedIssueTypeArray = $postArray['search_issue_type'];
        if (count($selectedIssueTypeArray) == 1 && $selectedIssueTypeArray[0] == -1) {
            $selectedIssueTypeArray = null;
        } else if (count($selectedIssueTypeArray)) {
            $result = array();
            for ($i = 0; $i < count($selectedIssueTypeArray); $i++) {
                $Ids = $selectedIssueTypeArray[$i];
                $result = array_merge($result, explode("#", $Ids));
            }
            $selectedIssueTypeArray = array_unique($result);
        }

        $selectedIssueStatusArray = $postArray['search_issue_status'];
        if (count($selectedIssueStatusArray) == 1 && $selectedIssueStatusArray[0] == -1) {
            $selectedIssueStatusArray = null;
        } else if (count($selectedIssueStatusArray)) {
            $result = array();
            for ($i = 0; $i < count($selectedIssueStatusArray); $i++) {
                $Ids = $selectedIssueStatusArray[$i];
                $result = array_merge($result, explode("#", $Ids));
            }
            $selectedIssueStatusArray = array_unique($result);
        }

        $selectedIssuePriorityArray = $postArray['search_issue_priority'];
        if (count($selectedIssuePriorityArray) == 1 && $selectedIssuePriorityArray[0] == -1) {
            $selectedIssuePriorityArray = null;
        } else if (count($selectedIssuePriorityArray)) {
            $result = array();
            for ($i = 0; $i < count($selectedIssuePriorityArray); $i++) {
                $Ids = $selectedIssuePriorityArray[$i];
                $result = array_merge($result, explode("#", $Ids));
            }
            $selectedIssuePriorityArray = array_unique($result);
        }

        $selectedIssueResolutionArray = $postArray['search_issue_resolution'];
        if (count($selectedIssueResolutionArray) == 1 && $selectedIssueResolutionArray[0] == -1) {
            $selectedIssueResolutionArray = null;
        } else if (count($selectedIssueResolutionArray)) {
            $result = array();
            for ($i = 0; $i < count($selectedIssueResolutionArray); $i++) {
                $Ids = $selectedIssueResolutionArray[$i];
                $result = array_merge($result, explode("#", $Ids));
            }
            $selectedIssueResolutionArray = array_unique($result);
        }

        $selectedUserAssigneeArray = $postArray['search_assignee'];
        if (count($selectedUserAssigneeArray) == 1 && $selectedUserAssigneeArray[0] == -1) {
            $selectedUserAssigneeArray = null;
        } else if (count($selectedUserAssigneeArray)) {
            $result = array();
            for ($i = 0; $i < count($selectedUserAssigneeArray); $i++) {
                $Ids = $selectedUserAssigneeArray[$i];
                $result = array_merge($result, explode("#", $Ids));
            }
            $selectedUserAssigneeArray = array_unique($result);
        }

        $selectedUserReporterArray = $postArray['search_reporter'];
        if (count($selectedUserReporterArray) == 1 && $selectedUserReporterArray[0] == -1) {
            $selectedUserReporterArray = null;
        } else if (count($selectedUserReporterArray)) {
            $result = array();
            for ($i = 0; $i < count($selectedUserReporterArray); $i++) {
                $Ids = $selectedUserReporterArray[$i];
                $result = array_merge($result, explode("#", $Ids));
            }
            $selectedUserReporterArray = array_unique($result);
        }

        $selectedProjectComponentArray = $postArray['search_component'];
        if (count($selectedProjectComponentArray) == 1 && $selectedProjectComponentArray[0] == -1) {
            $selectedProjectComponentArray = null;
        } else if (count($selectedProjectComponentArray)) {
            $result = array();
            for ($i = 0; $i < count($selectedProjectComponentArray); $i++) {
                $Ids = $selectedProjectComponentArray[$i];
                $result = array_merge($result, explode("#", $Ids));
            }
            $selectedProjectComponentArray = array_unique($result);
        }

        $selectedProjectFixVersionArray = $postArray['search_fix_version'];
        if (count($selectedProjectFixVersionArray) == 1 && $selectedProjectFixVersionArray[0] == -1) {
            $selectedProjectFixVersionArray = null;
        } else if (count($selectedProjectFixVersionArray)) {
            $result = array();
            for ($i = 0; $i < count($selectedProjectFixVersionArray); $i++) {
                $Ids = $selectedProjectFixVersionArray[$i];
                $result = array_merge($result, explode("#", $Ids));
            }
            $selectedProjectFixVersionArray = array_unique($result);
        }

        $selectedProjectAffectsVersionArray = $postArray['search_affects_version'];
        if (count($selectedProjectAffectsVersionArray) == 1 && $selectedProjectAffectsVersionArray[0] == -1) {
            $selectedProjectAffectsVersionArray = null;
        } else if (count($selectedProjectAffectsVersionArray)) {
            $result = array();
            for ($i = 0; $i < count($selectedProjectAffectsVersionArray); $i++) {
                $Ids = $selectedProjectAffectsVersionArray[$i];
                $result = array_merge($result, explode("#", $Ids));
            }
            $selectedProjectAffectsVersionArray = array_unique($result);
        }

        $search_date_due_after = $postArray['search_date_due_after'];
        $search_date_due_before = $postArray['search_date_due_before'];

        $search_date_created_after = $postArray['search_date_created_after'];
        $search_date_created_before = $postArray['search_date_created_before'];

        $searchParameters = array(
            'search_query' => $searchText,
            'description_flag' => $descriptionFlag,
            'comments_flag' => $commentsFlag,
            'project' => $selectedProjectArray,
            'assignee' => $selectedUserAssigneeArray,
            'reporter' => $selectedUserReporterArray,
            'type' => $selectedIssueTypeArray,
            'status' => $selectedIssueStatusArray,
            'priority' => $selectedIssuePriorityArray,
            'component' => $selectedProjectComponentArray,
            'resolution' => $selectedIssueResolutionArray,
            'filter' => $getFilter,
            'date_due_after' => $search_date_due_after,
            'date_due_before' => $search_date_due_before,
            'date_created_before' => $search_date_created_before,
            'date_created_after' => $search_date_created_after,
            'fix_version' => $selectedProjectFixVersionArray,
            'affects_version' => $selectedProjectAffectsVersionArray
        );

        if ($searchText) {
            $searchParameters['summary_flag'] = $summaryFlag;
        }

        // prepare the url
        foreach ($searchParameters as $key => $value) {
            if (is_array($value))
                $searchParameters[$key] = implode('|', $value);
            else if ($value == null) {
                unset($searchParameters[$key]);
            }
        }

        return $searchParameters;
    }

    public function deleteSLADataByIssueIdAndSLAId($issueID, $SLAId) {
        $query = "delete from yongo_issue_sla where yongo_issue_id = ? and help_sla_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueID, $SLAId);
        $stmt->execute();
    }

    public function deleteSLADataByIssueId($issueID) {
        $query = "delete from yongo_issue_sla where yongo_issue_id = ?";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueID);
        $stmt->execute();
    }

    public function updateSLAValueOnly($issueId, $SLAId, $value) {
        $query = "update yongo_issue_sla set `value` = ? where yongo_issue_id = ? and help_sla_id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $value, $issueId, $SLAId);
        $stmt->execute();
    }

    public function updateAssigneeRaw($issueId, $userAssigneeId) {
        $query = "update yongo_issue set `user_assigned_id` = ? where id = ? limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $userAssigneeId, $issueId);
        $stmt->execute();
    }

    public function getIssuesWithDueDateReminder() {
        $query = "select yongo_issue.id, yongo_issue.summary, general_user.id as user_id, general_user.first_name, general_user.last_name, " .
                 "general_user.client_id " .
                 "from general_user " .
                 "left join (yongo_issue.user_assigned_id = on general_user.id and datediff(yongo_issue.date_due, NOW()) >= general_user.remind_days_before_due_date) " .
                 "where yongo_issue.date_due > NOW() " .
                 "and general_user.yongo_issue.user_assigned_id is not null " .
                 "order by general_user.id";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows) {
            return $result;
        } else {
            return null;
        }
    }

    public function getAssigneeOnDate($issueId, $date) {
        $query = 'SELECT issue_history.new_value_id ' .
            'from issue_history ' .
            'WHERE issue_history.issue_id = ? ' .
            "and issue_history.field = 'assignee' " .
            "and issue_history.date_created <= ? " .
            "order by issue_history.id desc " .
            "limit 1";

        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("is", $issueId, $date);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows) {
            $data = $result->fetch_array(MYSQLI_ASSOC);
            return $data['new_value_id'];
        } else {
            $query = 'SELECT issue_history.old_value_id ' .
                'from issue_history ' .
                'WHERE issue_history.issue_id = ? ' .
                "and issue_history.field = 'assignee' " .
                "and issue_history.date_created > ? " .
                "order by issue_history.id asc " .
                "limit 1";

            $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
            $stmt->bind_param("is", $issueId, $date);
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows) {
                $data = $result->fetch_array(MYSQLI_ASSOC);
                return $data['old_value_id'];
            } else {
                $issue = UbirimiContainer::get()['repository']->get(Issue::class)->getByIdSimple($issueId);
                return $issue['user_assigned_id'];
            }
        }
    }
}