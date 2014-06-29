<?php
namespace Ubirimi\Yongo\Repository\Issue;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Repository\HelpDesk\SLA;
use Ubirimi\Repository\User\User;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;
use Ubirimi\Yongo\Repository\Issue\IssueCustomField;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Yongo\Repository\Issue\IssueWorkLog;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class Issue {

    const ISSUE_AFFECTED_VERSION_FLAG = 1;
    const ISSUE_FIX_VERSION_FLAG = 2;

    public static function getById($issueId, $loggedInUserId = null) {
        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = Issue::getByParameters($issueQueryParameters, $loggedInUserId);

        return $issue;
    }

    public static function getByParameters($parameters, $loggedInUserId = null, $queryWherePart = null) {

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
            'issue_main_table.original_estimate, issue_main_table.remaining_estimate, ';

        if (isset($parameters['sprint'])) {

            $query .= 'issue_parent.status_id as parent_status_id, issue_parent.user_assigned_id as parent_assignee, ' .
                      'issue_parent.nr as parent_nr, issue_parent.summary as parent_summary, project_parent.code as parent_project_code, ' .
                      'CONCAT(coalesce(issue_parent.id, \'\'), issue_main_table.id) as sort_sprint, ';
        }

        if ($loggedInUserId) {
            // deal with security scheme level

            // 1. user in security scheme level data
            $query .= '(SELECT max(issue_security_scheme_level_data.id)
                      from issue_security_scheme_level_data
                      left join user on user.id = issue_security_scheme_level_data.user_id
                      where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id
                      and user.id = ?) as security_check1, ';

            $parameterType .= 'i';
            $parameterArray[] = $loggedInUserId;

            // 2. user in group security scheme level data
            $query .= '(SELECT max(issue_security_scheme_level_data.id) ' .
                'from issue_security_scheme_level_data ' .
                'left join `group` on group.id = issue_security_scheme_level_data.group_id ' .
                'left join `group_data` on group_data.group_id = `group`.id ' .
                'left join user on user.id = group_data.user_id ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id and ' .
                'user.id = ?) as security_check2, ';

            $parameterType .= 'i';
            $parameterArray[] = $loggedInUserId;

            // 3. permission role in security scheme level data - user
            $query .= '(SELECT max(issue_security_scheme_level_data.id) ' .
                'from issue_security_scheme_level_data ' .
                'left join project_role_data on project_role_data.permission_role_id = issue_security_scheme_level_data.permission_role_id ' .
                'left join user on user.id = project_role_data.user_id ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id and ' .
                'user.id = ?) as security_check3, ';

            $parameterType .= 'i';
            $parameterArray[] = $loggedInUserId;

            // 4. permission role in security scheme level data - group
            $query .= '(SELECT max(issue_security_scheme_level_data.id) ' .
                'from issue_security_scheme_level_data ' .
                'left join project_role_data on project_role_data.permission_role_id = issue_security_scheme_level_data.permission_role_id ' .
                'left join `group` on group.id = project_role_data.group_id ' .
                'left join `group_data` on group_data.group_id = `group`.id ' .
                'left join user on user.id = group_data.user_id ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id and ' .
                'user.id = ?) as security_check4, ';

            $parameterType .= 'i';
            $parameterArray[] = $loggedInUserId;

            // 5. current_assignee in security scheme level data
            $query .= '(SELECT max(issue_security_scheme_level_data.id) ' .
                'from issue_security_scheme_level_data, user ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id and ' .
                'issue_security_scheme_level_data.current_assignee is not null and ' .
                'issue_main_table.user_assigned_id is not null and ' .
                'issue_main_table.user_assigned_id = user.id and ' .
                'user.id = ?) as security_check5, ';

            $parameterType .= 'i';
            $parameterArray[] = $loggedInUserId;

            // 6. reporter in security scheme level data
            $query .= '(SELECT max(issue_security_scheme_level_data.id) ' .
                'from issue_security_scheme_level_data, user ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id and ' .
                'issue_security_scheme_level_data.reporter is not null and ' .
                'issue_main_table.user_reported_id is not null and ' .
                'issue_main_table.user_reported_id = user.id and ' .
                'user.id = ?) as security_check6, ';

            $parameterType .= 'i';
            $parameterArray[] = $loggedInUserId;

            // 7. project_lead in security scheme level data

            $query .= '(SELECT max(issue_security_scheme_level_data.id) ' .
                'from issue_security_scheme_level_data, project, user ' .
                'where issue_security_scheme_level_data.issue_security_scheme_level_id = issue_main_table.security_scheme_level_id and ' .
                'project.id = issue_main_table.project_id and ' .
                'project.lead_id = user.id and ' .
                'issue_security_scheme_level_data.project_lead is not null and ' .
                'project.lead_id is not null and ' .
                'user.id = ?) as security_check7, ';

            $parameterType .= 'i';
            $parameterArray[] = $loggedInUserId;
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
            'LEFT JOIN user AS user_reported ON issue_main_table.user_reported_id = user_reported.id ' .
            'LEFT JOIN user AS user_assigned ON issue_main_table.user_assigned_id = user_assigned.id ' .
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

            if ($query_where_part_arr)
                $queryWhere .= '(' . implode(' OR ', $query_where_part_arr) . ') AND ';
        }

        if (isset($parameters['project'])) {

            if (is_array($parameters['project'])) {
                if (!in_array(-1, $parameters['project']))
                    $queryWhere .= ' issue_main_table.project_id IN (' . implode(',', $parameters['project']) . ') AND ';
                else
                    $queryWhere .= ' issue_main_table.project_id IN (' . implode(',', $parameters['project']) . ') AND ';
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
                        $parameters['assignee'][$index] = $loggedInUserId;
                    }
                }
                if (!in_array(-1, $parameters['assignee']))
                    $queryWhere .= ' issue_main_table.user_assigned_id IN (' . implode(',', $parameters['assignee']) . ') AND ';
            } else {
                if ($parameters['assignee']) {
                    if ($parameters['assignee'] == 'current_user') {
                        $parameters['assignee'] = $loggedInUserId;
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

        if (strtoupper(substr($queryWhere, strlen($queryWhere) - 4, 4)) == 'AND ')
            $queryWhere = substr($queryWhere, 0, strlen($queryWhere) - 4);

        if (substr($queryWhere, strlen($queryWhere) - 4, 4) == 'AND ')
            $queryWhere = substr($query, 0, strlen($queryWhere) - 4);
        $sortColumn = null;
        if (isset($parameters['sort'])) {
            switch ($parameters['sort']) {
                case 'code':
                    $sortColumn = 'issue_main_table.id';
                    break;
                case 'type':
                    $sortColumn = 'type';
                    break;
                case 'priority':
                    $sortColumn = 'priority';
                    break;
                case 'status':
                    $sortColumn = 'status';
                    break;
                case 'summary':
                    $sortColumn = 'issue_main_table.summary';
                    break;
                case 'reported_by':
                    $sortColumn = 'user_reported.id';
                    break;
                case 'assignee':
                    $sortColumn = 'user_assigned.id';
                    break;
                case 'created':
                    $sortColumn = 'issue_main_table.date_created';
                    break;
                case 'updated':
                    $sortColumn = 'issue_main_table.date_updated';
                    break;
                case 'parent':
                    $sortColumn = 'issue_main_table.parent_id';
                    break;
                case 'sprint':
                    $sortColumn = 'sort_sprint';
                    break;
            }
        }
        if ($queryWherePart) {
            $queryWhere .= $queryWherePart;
        }
        if ($queryWhere != '')
            $query .= ' WHERE ' . $queryWhere;

        $query .= ' GROUP BY issue_main_table.id ';

        if ($loggedInUserId)
            $query .= ' HAVING ((security_check1 > 0 or security_check2 > 0 or security_check3 > 0 or security_check4 > 0 or security_check5 > 0 or security_check6 > 0 or security_check7 > 0) ' .
                        ' OR (issue_main_table.security_scheme_level_id is null and security_check1 is null and security_check2 is null and security_check3 is null and security_check4 is null and security_check5 is null and security_check6 is null and security_check7 is null)) ';
        if ($sortColumn)
            $query .= 'ORDER BY ' . $sortColumn;

        if (isset($parameters['sort']) && isset($parameters['sort_order']) && $sortColumn)
            $query .= ' ' . $parameters['sort_order'];

        if (isset($parameters['page']))
            $query .= ' LIMIT ' . (($parameters['page'] - 1) * $parameters['issues_per_page']) . ', ' . ($parameters['issues_per_page']);

//echo $query;
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            if ($queryWhere != '') {
                $param_arr_ref = array();

                foreach ($parameterArray as $key => $value)
                    $param_arr_ref[$key] = &$parameterArray[$key];

                if ($parameterType != '')
                    call_user_func_array(array($stmt, "bind_param"), array_merge(array($parameterType), $param_arr_ref));
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result->num_rows)
                return null;

            if (isset($parameters['page'])) {

                $q = "SELECT FOUND_ROWS() as count;";
                $stmt = UbirimiContainer::get()['db.connection']->prepare($q);
                $stmt->execute();
                $result_total = $stmt->get_result();
                $count = $result_total->fetch_array(MYSQLI_ASSOC);

                return array($result, $count['count']);
            } else if ((isset($parameters['issue_id']) && !is_array($parameters['issue_id'])) || isset($parameters['nr'])) {

                return $result->fetch_array(MYSQLI_ASSOC);
            } else {
                return $result;
            }
        }

        return null;
    }

    public static function setUnassignedById($issueId) {
        $query = 'update yongo_issue SET user_assigned_id = null where id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueId);
            $stmt->execute();
        }
    }

    public static function get2DimensionalFilter($projectId, $resultType = 'array') {
        $query = 'select user.id, user.first_name, user.last_name, yongo_issue.status_id, count(yongo_issue.status_id) as count ' .
                    'from yongo_issue ' .
                    'left join user on user.id = yongo_issue.user_assigned_id ' .
                    'where yongo_issue.project_id = ? ' .
                    'group by user.id, yongo_issue.status_id';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $projectId);
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
    }

    public static function updateField($issueId, $field_type, $newValue) {
        $query = 'update yongo_issue SET ' . $field_type . ' = ? where id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $newValue, $issueId);
            $stmt->execute();
        }
    }

    public static function updateResolution($projectIdArray, $oldResolutionId, $newResolutionId) {
        $projectSQL = implode(', ', $projectIdArray);
        $query_update = 'update yongo_issue SET resolution_id = ? where resolution_id = ? and project_id IN (' . $projectSQL . ')';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query_update)) {
            $stmt->bind_param("ii", $newResolutionId, $oldResolutionId);
            $stmt->execute();
        }
    }

    public static function updatePriority($projectIdArray, $oldPriorityId, $newPriorityId) {
        $projectSQL = implode(', ', $projectIdArray);
        $query_update = 'update yongo_issue SET priority_id = ? where priority_id = ? and project_id IN (' . $projectSQL . ')';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query_update)) {
            $stmt->bind_param("ii", $newPriorityId, $oldPriorityId);
            $stmt->execute();
        }
    }

    public static function updateType($projectIdArray, $oldTypeId, $newTypeId) {
        $projectSQL = implode(', ', $projectIdArray);
        $query_update = 'update yongo_issue SET type_id = ? where type_id = ? and project_id IN (' . $projectSQL . ')';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query_update)) {
            $stmt->bind_param("ii", $newTypeId, $oldTypeId);
            $stmt->execute();
        }
    }

    public static function addHistory($issueId, $userId, $field, $old_value, $new_value, $now_date) {
        if (!$old_value)
            $old_value = 'NULL';

        if (!$new_value)
            $new_value = 'NULL';

        $query = "INSERT INTO issue_history(issue_id, by_user_id, field, old_value, new_value, date_created) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iissss", $issueId, $userId, $field, $old_value, $new_value, $now_date);
            $stmt->execute();
        }
    }

    public static function deleteById($issueId) {
        $query = 'DELETE FROM issue_comment WHERE issue_id = ?';
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueId);
            $stmt->execute();
        }

        $query = 'DELETE FROM issue_history WHERE issue_id = ?';
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueId);
            $stmt->execute();
        }

        $query = 'DELETE FROM issue_component WHERE issue_id = ?';
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueId);
            $stmt->execute();
        }

        $query = 'DELETE FROM issue_version WHERE issue_id = ?';
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueId);
            $stmt->execute();
        }

        $query = 'DELETE FROM agile_board_sprint_issue WHERE issue_id = ?';
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueId);
            $stmt->execute();
        }

        $query = 'DELETE from yongo_issue WHERE id = ?';
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueId);
            $stmt->execute();
        }
    }

    public static function addRaw($projectId, $date, $data) {
        $issueNumber = Issue::getAvailableIssueNumber($projectId);

        $query = "INSERT INTO yongo_issue(project_id, priority_id, status_id, type_id, user_assigned_id, user_reported_id, nr, " .
            "summary, description, environment, date_created, date_due) " .
            "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iiiiiiisssss", $projectId, $data['priority'], $data['status'], $data['type'], $data['assignee'],
                $data['reporter'], $issueNumber, $data['summary'], $data['description'],
                $data['environment'], $date, $data['due_date']);
            $stmt->execute();

            return array(UbirimiContainer::get()['db.connection']->insert_id, $issueNumber);
        }
    }

    public static function addBugzilla($project, $currentDate, $issueSystemFields, $loggedInUserId, $parentIssueId = null, $systemTimeTrackingDefaultUnit = null, $statusId) {

        $issueNumber = Issue::getAvailableIssueNumber($project['id']);
        $workflowUsed = Project::getWorkflowUsedForType($project['id'], $issueSystemFields['type']);

        $query = "INSERT INTO yongo_issue(project_id, resolution_id, priority_id, status_id, type_id, user_assigned_id, user_reported_id, nr, " .
            "summary, description, environment, date_created, date_due, parent_id, security_scheme_level_id, original_estimate, remaining_estimate, helpdesk_flag) " .
            "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if (!array_key_exists('reporter', $issueSystemFields) || $issueSystemFields['reporter'] == null) {
            $issueSystemFields['reporter'] = $loggedInUserId;
        }

        $securityLevel = null;
        if (isset($issueSystemFields[Field::FIELD_ISSUE_SECURITY_LEVEL])) {
            $securityLevel = $issueSystemFields[Field::FIELD_ISSUE_SECURITY_LEVEL];
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

        if (is_numeric($time_tracking_original_estimate))
            $time_tracking_original_estimate .=  $systemTimeTrackingDefaultUnit;
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param(
                "iiiiiiiisssssiissi",
                $project['id'],
                $issueSystemFields['resolution'],
                $issueSystemFields['priority'],
                $statusId,
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
                $issueSystemFields['helpdesk_flag']
            );

            $stmt->execute();
            return array(UbirimiContainer::get()['db.connection']->insert_id, $issueNumber);
        }
    }

    public static function add($project, $currentDate, $issueSystemFields, $loggedInUserId, $parentIssueId = null, $systemTimeTrackingDefaultUnit = null) {

        $issueNumber = Issue::getAvailableIssueNumber($project['id']);
        $workflowUsed = Project::getWorkflowUsedForType($project['id'], $issueSystemFields['type']);

        $statusData = Workflow::getDataForCreation($workflowUsed['id']);
        $StatusId = $statusData['linked_issue_status_id'];

        $query = "INSERT INTO yongo_issue(project_id, resolution_id, priority_id, status_id, type_id, user_assigned_id, user_reported_id, nr, " .
                                   "summary, description, environment, date_created, date_due, parent_id, security_scheme_level_id, original_estimate, remaining_estimate, helpdesk_flag) " .
                 "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if (!array_key_exists('reporter', $issueSystemFields) || $issueSystemFields['reporter'] == null) {
            $issueSystemFields['reporter'] = $loggedInUserId;
        }

        $securityLevel = null;
        if (isset($issueSystemFields[Field::FIELD_ISSUE_SECURITY_LEVEL])) {
            $securityLevel = $issueSystemFields[Field::FIELD_ISSUE_SECURITY_LEVEL];
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

        if (is_numeric($time_tracking_original_estimate))
            $time_tracking_original_estimate .=  $systemTimeTrackingDefaultUnit;
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param(
                "iiiiiiiisssssiissi",
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
                $issueSystemFields['helpdesk_flag']
            );

            $stmt->execute();
            return array(UbirimiContainer::get()['db.connection']->insert_id, $issueNumber);
        }
    }

    public static function getByIdSimple($issueId) {
        $query = 'SELECT * from yongo_issue where id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $issueId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                $result = $result->fetch_array(MYSQLI_ASSOC);

                return $result;
            } else return null;
        }
    }

    public static function getAvailableIssueNumber($projectId) {
        $query = 'SELECT issue_number ' .
                    'FROM project ' .
                    'WHERE id = ? ' .
                    'ORDER BY id desc ' .
                    'LIMIT 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
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
            } else return 1;
        }
    }

    public static function addComponentVersion($issueId, $values, $table, $version_flag = null) {
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

            if (!$version_flag) {
                $query .= '(?, ?), ';
                $bind_param_str .= 'ii';
            } else {
                $query .= '(?, ?, ?), ';
                $bind_param_str .= 'iii';
            }
            $bind_param_arr[] = $issueId;
            $bind_param_arr[] = (int)$value;
            if ($version_flag) $bind_param_arr[] = $version_flag;
        }

        $query = substr($query, 0, strlen($query) - 2);

        $bind_param_arr_ref = array();
        foreach ($bind_param_arr as $key => $value)
            $bind_param_arr_ref[$key] = &$bind_param_arr[$key];

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            call_user_func_array(array($stmt, "bind_param"), array_merge(array($bind_param_str), $bind_param_arr_ref));
            $stmt->execute();
        }
    }

    public static function updateById($issueId, $data, $updateDate) {

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

                case Field::FIELD_ISSUE_SECURITY_LEVEL:
                    $query .= 'security_scheme_level_id = ?,';
                    $paramType .= 'i';
                    if ($data[FIELD::FIELD_ISSUE_SECURITY_LEVEL] == -1)
                        $paramValues[] = null;
                    else
                        $paramValues[] = $data[FIELD::FIELD_ISSUE_SECURITY_LEVEL];
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

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            call_user_func_array(array($stmt, "bind_param"), array_merge(array($paramType), $bind_param_arr_ref));
            $stmt->execute();
        }

        if (array_key_exists(Field::FIELD_AFFECTS_VERSION_CODE, $data)) {
            IssueVersion::deleteByIssueId($issueId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
            if ($data[Field::FIELD_AFFECTS_VERSION_CODE])
                Issue::addComponentVersion($issueId, $data['affects_version'], 'issue_version', Issue::ISSUE_AFFECTED_VERSION_FLAG);
        }

        if (array_key_exists(Field::FIELD_FIX_VERSION_CODE, $data)) {
            IssueVersion::deleteByIssueId($issueId, Issue::ISSUE_FIX_VERSION_FLAG);
            if ($data[Field::FIELD_FIX_VERSION_CODE])
                Issue::addComponentVersion($issueId, $data['fix_version'], 'issue_version', Issue::ISSUE_FIX_VERSION_FLAG);
        }

        if (array_key_exists(Field::FIELD_COMPONENT_CODE, $data)) {
            IssueComponent::deleteByIssueId($issueId);
            if ($data[Field::FIELD_COMPONENT_CODE])
                Issue::addComponentVersion($issueId, $data['component'], 'issue_component');
        }
    }

    private static function issueFieldChanged($fieldCode, $oldIssueData, $newIssueData) {

        $fieldChanged = false;

        if (isset($newIssueData[$fieldCode]) && $newIssueData[$fieldCode] != $oldIssueData[$fieldCode]) {
            $fieldChanged = true;
        }

        return $fieldChanged;
    }

    public static function computeDifference($oldIssueData, $newIssueData) {

        $fieldChanges = array();
        $issueId = $oldIssueData['id'];

        if (Issue::issueFieldChanged(Field::FIELD_SUMMARY_CODE, $oldIssueData, $newIssueData)) {
            $fieldChanges[] = array(Field::FIELD_SUMMARY_CODE, $oldIssueData[Field::FIELD_SUMMARY_CODE], $newIssueData[Field::FIELD_SUMMARY_CODE]);
        }

        if (Issue::issueFieldChanged(Field::FIELD_DESCRIPTION_CODE, $oldIssueData, $newIssueData)) {
            $fieldChanges[] = array(Field::FIELD_DESCRIPTION_CODE, $oldIssueData[Field::FIELD_DESCRIPTION_CODE], $newIssueData[Field::FIELD_DESCRIPTION_CODE]);
        }
        if (Issue::issueFieldChanged(Field::FIELD_ENVIRONMENT_CODE, $oldIssueData, $newIssueData)) {
            $fieldChanges[] = array(Field::FIELD_ENVIRONMENT_CODE, $oldIssueData[Field::FIELD_ENVIRONMENT_CODE], $newIssueData[Field::FIELD_ENVIRONMENT_CODE]);
        }

        if (Issue::issueFieldChanged(Field::FIELD_DUE_DATE_CODE, $oldIssueData, $newIssueData)) {
            $fieldChanges[] = array(Field::FIELD_DUE_DATE_CODE, $oldIssueData[Field::FIELD_DUE_DATE_CODE], $newIssueData[Field::FIELD_DUE_DATE_CODE]);
        }

        if (Issue::issueFieldChanged(Field::FIELD_ISSUE_TYPE_CODE, $oldIssueData, $newIssueData)) {
            $field_changed_old_value_row = IssueType::getById($oldIssueData[Field::FIELD_ISSUE_TYPE_CODE]);
            $field_changed_new_value_row = IssueType::getById($newIssueData[Field::FIELD_ISSUE_TYPE_CODE]);
            $fieldChanges[] = array(Field::FIELD_ISSUE_TYPE_CODE, $field_changed_old_value_row['name'], $field_changed_new_value_row['name']);
        }

        if (Issue::issueFieldChanged(Field::FIELD_PRIORITY_CODE, $oldIssueData, $newIssueData)) {
            $field_changed_old_value_row = IssueSettings::getById($oldIssueData[Field::FIELD_PRIORITY_CODE], 'priority');
            $field_changed_new_value_row = IssueSettings::getById($newIssueData[Field::FIELD_PRIORITY_CODE], 'priority');

            $fieldChanges[] = array(Field::FIELD_PRIORITY_CODE, $field_changed_old_value_row['name'], $field_changed_new_value_row['name']);
        }

        if (Issue::issueFieldChanged(Field::FIELD_STATUS_CODE, $oldIssueData, $newIssueData)) {
            $field_changed_old_value_row = IssueSettings::getById($oldIssueData[Field::FIELD_STATUS_CODE], 'status');
            $field_changed_new_value_row = IssueSettings::getById($newIssueData[Field::FIELD_STATUS_CODE], 'status');
            $fieldChanges[] = array(Field::FIELD_STATUS_CODE, $field_changed_old_value_row['name'], $field_changed_new_value_row['name']);
        }

        if (Issue::issueFieldChanged(Field::FIELD_RESOLUTION_CODE, $oldIssueData, $newIssueData)) {
            $field_changed_old_value_row = IssueSettings::getById($oldIssueData[Field::FIELD_RESOLUTION_CODE], 'resolution');
            $field_changed_new_value_row = IssueSettings::getById($newIssueData[Field::FIELD_RESOLUTION_CODE], 'resolution');

            $fieldChanges[] = array(Field::FIELD_RESOLUTION_CODE, $field_changed_old_value_row['name'], $field_changed_new_value_row['name']);
        }

        if (Issue::issueFieldChanged(Field::FIELD_ASSIGNEE_CODE, $oldIssueData, $newIssueData)) {

            $field_changed_old_value_row = User::getById($oldIssueData[Field::FIELD_ASSIGNEE_CODE]);
            $fieldChangedOldValue = $field_changed_old_value_row['first_name'] . ' ' . $field_changed_old_value_row['last_name'];

            $field_changed_new_value_row = User::getById($newIssueData[Field::FIELD_ASSIGNEE_CODE]);
            $fieldChangedNewValue = $field_changed_new_value_row['first_name'] . ' ' . $field_changed_new_value_row['last_name'];
            $fieldChanges[] = array(Field::FIELD_ASSIGNEE_CODE, $fieldChangedOldValue, $fieldChangedNewValue);
        }

        if (Issue::issueFieldChanged(Field::FIELD_REPORTER_CODE, $oldIssueData, $newIssueData)) {
            $field_changed_old_value_row = User::getById($oldIssueData[Field::FIELD_REPORTER_CODE]);
            $fieldChangedOldValue = $field_changed_old_value_row['first_name'] . ' ' . $field_changed_old_value_row['last_name'];
            $field_changed_new_value_row = User::getById($newIssueData[Field::FIELD_REPORTER_CODE]);
            $fieldChangedNewValue = $field_changed_new_value_row['first_name'] . ' ' . $field_changed_new_value_row['last_name'];
            $fieldChanges[] = array(Field::FIELD_REPORTER_CODE, $fieldChangedOldValue, $fieldChangedNewValue);
        }

        // deal with the components
        if (array_key_exists('component', $newIssueData) && null !== $newIssueData['component']) {
            $oldComponents = IssueComponent::getByIssueId($issueId);
            $oldVersionsAffected = IssueVersion::getByIssueId($issueId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
            $oldVersionsTargeted = IssueVersion::getByIssueId($issueId, Issue::ISSUE_FIX_VERSION_FLAG);

            $oldComponentsArray = array();
            while ($oldComponents && $c = $oldComponents->fetch_array(MYSQLI_ASSOC))
                $oldComponentsArray[] = $c['project_component_id'];

            if ((count($oldComponentsArray) != count($newIssueData['component'])) || count(array_diff($oldComponentsArray, $newIssueData['component']))) {
                $projectComponents = Project::getComponents($oldIssueData['issue_project_id']);

                $project_components_names = array();
                while ($comp = $projectComponents->fetch_array(MYSQLI_ASSOC))
                    $project_components_names[$comp['id']] = $comp['name'];

                $old_components_arr_names = array();
                $new_components_arr_names = array();
                for ($i = 0; $i < count($oldComponentsArray); $i++)
                    $old_components_arr_names[] = $project_components_names[$oldComponentsArray[$i]];

                if ($newIssueData['component'])
                    for ($i = 0; $i < count($newIssueData['component']); $i++)
                        $new_components_arr_names[] = $project_components_names[$newIssueData['component'][$i]];

                $fieldChanges[] = array(Field::FIELD_COMPONENT_CODE, implode(', ', $old_components_arr_names), implode(', ', $new_components_arr_names));
            }
        }

        // deal with the affected versions
        if (array_key_exists('affects_version', $newIssueData) && null !== $newIssueData['affects_version']) {
            $old_versions_affected_arr = array();
            $oldValueArray = array();
            while (isset($oldVersionsAffected) && ($v = $oldVersionsAffected->fetch_array(MYSQLI_ASSOC)))
                $old_versions_affected_arr[] = $v['project_version_id'];

            for ($i = 0; $i < count($old_versions_affected_arr); $i++) {
                $versionData = Project::getVersionById($old_versions_affected_arr[$i]);
                $oldValueArray[] = $versionData['name'];
            }
            $oldValue = implode(', ', $oldValueArray);

            if (isset($newIssueData['affects_version']) && !is_array($newIssueData['affects_version']))
                $newIssueData['affects_version'] = array($newIssueData['affects_version']);

            $newValueArray = array();
            if (isset($newIssueData['affects_version'])) {
                for ($i = 0; $i < count($newIssueData['affects_version']); $i++) {
                    $versionData = Project::getVersionById($newIssueData['affects_version'][$i]);
                    $newValueArray[] = $versionData['name'];
                }
            }

            if ((count($old_versions_affected_arr) != count($newIssueData['affects_version'])) || count(array_diff($old_versions_affected_arr, $newIssueData['affects_version']))) {
                if ($oldValue != implode(', ', $newValueArray))
                    $fieldChanges[] = array(Field::FIELD_AFFECTS_VERSION_CODE, $oldValue, implode(', ', $newValueArray));
            }
        }

        // deal with the fix versions
        if (array_key_exists('fix_version', $newIssueData) && null !== $newIssueData['fix_version']) {
            $old_versions_targeted_arr = array();
            $oldValueArray = array();
            while (isset($oldVersionsTargeted) && ($v = $oldVersionsTargeted->fetch_array(MYSQLI_ASSOC)))
                $old_versions_targeted_arr[] = $v['project_version_id'];

            for ($i = 0; $i < count($old_versions_targeted_arr); $i++) {
                $versionData = Project::getVersionById($old_versions_targeted_arr[$i]);
                $oldValueArray[] = $versionData['name'];
            }
            $oldValue = implode(', ', $oldValueArray);

            if (isset($newIssueData['fix_version']) && !is_array($newIssueData['fix_version']))
                $newIssueData['fix_version'] = array($newIssueData['fix_version']);

            $newValueArray = array();
            if (isset($newIssueData['fix_version'])) {
                for ($i = 0; $i < count($newIssueData['fix_version']); $i++) {
                    $versionData = Project::getVersionById($newIssueData['fix_version'][$i]);
                    $newValueArray[] = $versionData['name'];
                }
            }

            if ((count($old_versions_targeted_arr) != count($newIssueData['fix_version'])) || count(array_diff($old_versions_targeted_arr, $newIssueData['fix_version']))) {
                if ($oldValue != implode(', ', $newValueArray))
                    $fieldChanges[] = array(Field::FIELD_FIX_VERSION_CODE, $oldValue, implode(', ', $newValueArray));
            }
        }

        return $fieldChanges;
    }

    public static function updateHistory($issueId, $loggedInUserId, $fieldChanges, $currentDate) {

        for ($i = 0; $i < count($fieldChanges); $i++) {
            if ($fieldChanges[$i][0] != 'comment')
                Issue::addHistory($issueId, $loggedInUserId, $fieldChanges[$i][0], $fieldChanges[$i][1], $fieldChanges[$i][2], $currentDate);
        }
    }

    public static function getAll($filters = array()) {
        $query = 'select id from yongo_issue ' .
                 'where 1 = 1';

        if (!empty($filters['today'])) {
            $query .= " and DATE(date_created) = DATE(NOW())";
        }

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows)
                return $result;
            else
                return false;
        }
    }

    public static function updateSecurityLevel($clientId, $issueSecuritySchemeLevelId, $newIssueSecuritySchemeLevelId) {
        $query = 'select yongo_issue.id ' .
            'from yongo_issue ' .
            'left join project on project.id = yongo_issue.project_id ' .
            'where project.client_id = ? and ' .
            'yongo_issue.security_scheme_level_id = ?';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
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
    }

    public static function setAffectedVersion($issueId, $projectVersionId) {
        $query = "INSERT INTO issue_version(issue_id, project_version_id, affected_targeted_flag) VALUES (?, ?, ?)";
        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {

            $versionType = self::ISSUE_AFFECTED_VERSION_FLAG;
            $stmt->bind_param("iii", $issueId, $projectVersionId, $versionType);
            $stmt->execute();
        }
    }

    public static function updateAssignee($clientId, $issueId, $loggedInUserId, $userAssignedId, $comment = null) {
        $issueData = Issue::getByParameters(array('issue_id' => $issueId), $loggedInUserId);

        $oldUserAssignedName = $issueData['ua_first_name'] . ' ' . $issueData['ua_last_name'];

        $userAssigned = User::getById($userAssignedId);
        $newUserAssignedName = $userAssigned['first_name'] . ' ' . $userAssigned['last_name'];

        if ($userAssignedId != -1)
            Issue::updateField($issueId, 'user_assigned_id', $userAssignedId);
        else
            Issue::setUnassignedById($issueId);
        $oldAssignee = User::getById($issueData['assignee']);
        $newAssignee = User::getById($userAssignedId);

        $oldAssigneeName = $oldAssignee['first_name'] . ' ' . $oldAssignee['last_name'];
        $newAssigneeName = $newAssignee['first_name'] . ' ' . $newAssignee['last_name'];

        $date = Util::getCurrentDateTime(UbirimiContainer::get()['session']->get('client/settings/timezone'));
        Issue::addHistory($issueId, $loggedInUserId, Field::FIELD_ASSIGNEE_CODE, $oldAssigneeName, $newAssigneeName, $date);

        if (!empty($comment))
            IssueComment::add($issueId, $loggedInUserId, $comment, $date);

        $issueData = Issue::getByParameters(array('issue_id' => $issueId), $loggedInUserId);
        $project = Project::getById($issueData['issue_project_id']);

        $smtpSettings = UbirimiContainer::get()['session']->get('client/settings/smtp');
        if ($smtpSettings) {
            Email::$smtpSettings = $smtpSettings;
            Email::triggerAssignIssueNotification($clientId, $issueData, $oldUserAssignedName, $newUserAssignedName, $project, $loggedInUserId, $comment);
        }
    }

    public static function move($issueId, $newProjectId, $newIssueTypeId, $newSubTaskIssueTypeIds) {

        $nextNumber = Issue::getAvailableIssueNumber($newProjectId);
        $query = 'update yongo_issue SET project_id = ?, type_id = ?, nr = ? where id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iisi", $newProjectId, $newIssueTypeId, $nextNumber, $issueId);
            $stmt->execute();
        }
        $stmt->close();

        // update last issue number for this project
        Project::updateLastIssueNumber($newProjectId, $nextNumber);

        $subTasks = Issue::getByParameters(array('parent_id' => $issueId));
        if ($subTasks) {
            while ($issue = $subTasks->fetch_array(MYSQLI_ASSOC)) {
                if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                    $nextNumber = Issue::getAvailableIssueNumber($newProjectId);

                    $subTaskId = $issue['id'];
                    $stmt->bind_param("iisi", $newProjectId, $newIssueTypeId, $nextNumber, $subTaskId);
                    $stmt->execute();

                    // update last issue number for this project
                    Project::updateLastIssueNumber($newProjectId, $nextNumber);

                    IssueVersion::deleteByIssueId($subTaskId, Issue::ISSUE_FIX_VERSION_FLAG);
                    IssueVersion::deleteByIssueId($subTaskId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
                    IssueComponent::deleteByIssueId($subTaskId);

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
    }

    public static function prepareDataForSearchFromURL($data, $issuesPerPage) {
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

    public static function prepareWhereClauseFromQueue($queueDefinition, $userId, $projectId, $clientId) {

        $value = mb_strtolower($queueDefinition);
        $SLAs = SLA::getByProjectId($projectId);
        while ($SLAs && $SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {
            if (stripos($value, $SLA['name'])) {
                $slaId = $SLA['id'];

                $sqlQueryPart = '(select value from yongo_issue_sla where yongo_issue_id = issue_main_table.id and help_sla_id = ' . $slaId . ' limit 1)';
                $value = str_ireplace($SLA['name'], $sqlQueryPart, $value);
            }
        }
        $value = str_ireplace('assignee', 'issue_main_table.user_assigned_id', $value);

        $value = str_ireplace('currentUser()', $userId, $value);
        $value = str_ireplace('type', 'issue_main_table.type_id', $value);
        $value = str_ireplace('status', 'issue_main_table.status_id', $value);
        $value = str_ireplace('resolution', 'issue_main_table.resolution_id', $value);
        $value = str_ireplace('= unresolved', 'IS NULL', $value);

        $statuses = IssueSettings::getAllIssueSettings('status', $clientId);
        $priorities = IssueSettings::getAllIssueSettings('priority', $clientId);
        $resolutions = IssueSettings::getAllIssueSettings('resolution', $clientId);
        $types = IssueType::getAll($clientId);

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

    public static function addSLAData($issueId, $slaId, $offset) {
        $query = "insert into yongo_issue_sla(yongo_issue_id, help_sla_id, `value`) values (?, ?, ?)";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("iii", $issueId, $slaId, $offset);
            $stmt->execute();
        }
    }

    public static function updateSLADataForProject($clientId, $projectId, $userId, $clientSettings) {
        $SLAs = SLA::getByProjectId($projectId);

        if ($SLAs) {

            $issueQueryParameters = array('project' => $projectId);
            $issues = Issue::getByParameters($issueQueryParameters, $userId);

            // check issue against the slas
            while ($SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {

                while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                    $slaData = SLA::getOffsetForIssue($SLA, $issue, $clientId, $clientSettings);

                    if ($slaData[0]) {
                        Issue::updateSLAValueOnly($issue['id'], $SLA['id'], $slaData[0]);
                    }
                }
                if ($issues) {
                    $issues->data_seek(0);
                }
            }
        }
    }

    public static function updateSLAStopped($issueId, $SLAId, $dateStopped) {
        $query = "update yongo_issue_sla set stopped_flag = 1, stopped_date = ? where yongo_issue_id = ? and help_sla_id = ? limit 1";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("sii", $dateStopped, $issueId, $SLAId);
            $stmt->execute();
        }
    }

    public static function checkStoppedSLA($issueId, $SLAId) {
        $query = 'SELECT stopped_flag from yongo_issue_sla where yongo_issue_id = ? AND help_sla_id = ? limit 1';

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("ii", $issueId, $SLAId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows) {
                $row = $result->fetch_array(MYSQLI_ASSOC);

                return $row['stopped_flag'];
            } else return null;
        }
    }

    public static function updateSLAValue($issue, $clientId, $clientSettings) {

        $slasPrintData = array();
        $atLeastOneSLA = false;
        $projectId = $issue['issue_project_id'];
        $SLAs = SLA::getByProjectId($projectId);

        if ($SLAs) {
            $atLeastOneSLA = false;
            // check issue against the SLAs
            while ($SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {
                $slaData = SLA::getOffsetForIssue($SLA, $issue, $clientId, $clientSettings);

                if ($slaData) {
                    $slasPrintData[$SLA['id']] = array('name' => $SLA['name'],
                        'offset' => $slaData[0],
                        'goal' => $slaData[1],
                        'started_flag' => $slaData[2],
                        'goal_id' => $slaData[3],
                        'started_date' => $slaData[4]);
                    if ($slaData[0]) {
                        $atLeastOneSLA = true;
                    }
                }
            }

            foreach ($slasPrintData as $slaId => $data) {
                if ($data['started_flag']) {
                    SLA::updateDataForSLA($issue['id'], $slaId, $data['offset'], $data['started_flag'], $data['goal_id'], $data['started_date']);
                }
            }
        }

        return array($slasPrintData, $atLeastOneSLA);
    }

    public static function addPlainSLAData($issueId, $projectId) {
        $SLAs = SLA::getByProjectId($projectId);
        if ($SLAs) {
            while ($SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {
                $query = "INSERT INTO yongo_issue_sla(yongo_issue_id, help_sla_id) VALUES (?, ?)";
                if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
                    $stmt->bind_param("ii", $issueId, $SLA['id']);
                    $stmt->execute();
                }
            }
        }
    }

    public static function clearSLAData($slaId) {
        $query = "update yongo_issue_sla set help_sla_goal_id = NULL, started_flag = 0, stopped_flag = 0, started_date = NULL, value = NULL where help_sla_id = ?";

        if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
            $stmt->bind_param("i", $slaId);
            $stmt->execute();
        }
    }

    public static function getSearchParameters($projectsForBrowsing, $clientId, $helpDeskFlag = 0) {

        $projectsForBrowsing->data_seek(0);
        $projectIds = Util::getAsArray($projectsForBrowsing, array('id'));

        $allClientIssueTypes = IssueType::getByProjects($projectIds);
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

        $allClientIssueStatuses = IssueSettings::getAllIssueSettings('status', $clientId);
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

        $allClientIssuePriorities = IssueSettings::getAllIssueSettings('priority', $clientId);

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

        $allClientIssueResolutions = IssueSettings::getAllIssueSettings('resolution', $clientId);
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
            $allClientUsers = User::getByClientId($clientId);
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

            $allClientUsers = User::getByClientId($clientId, 1);
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
            $allClientUsers = User::getByClientId($clientId);
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
        $allProjectsComponents = Project::getComponents($projectIds);
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
        $allProjectsVersions = Project::getVersions($projectIds);
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
    
    public static function prepareDataForSearchFromPostGet($projectIds, $postArray, $getArray) {
        
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

        $searchParameters = array('search_query' => $searchText, 'description_flag' => $descriptionFlag, 'comments_flag' => $commentsFlag,
            'project' => $selectedProjectArray, 'assignee' => $selectedUserAssigneeArray, 'reporter' => $selectedUserReporterArray,
            'type' => $selectedIssueTypeArray, 'status' => $selectedIssueStatusArray, 'priority' => $selectedIssuePriorityArray,
            'component' => $selectedProjectComponentArray, 'resolution' => $selectedIssueResolutionArray, 'filter' => $getFilter, 'date_due_after' => $search_date_due_after,
            'date_due_before' => $search_date_due_before, 'date_created_before' => $search_date_created_before, 'date_created_after' => $search_date_created_after,
            'fix_version' => $selectedProjectFixVersionArray, 'affects_version' => $selectedProjectAffectsVersionArray);

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

    public static function deleteSLADataByIssueIdAndSLAId($issueID, $SLAId) {
        $query = "delete from yongo_issue_sla where yongo_issue_id = ? and help_sla_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("ii", $issueID, $SLAId);
        $stmt->execute();
    }

    public static function deleteSLADataByIssueId($issueID) {
        $query = "delete from yongo_issue_sla where yongo_issue_id = ?";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("i", $issueID);
        $stmt->execute();
    }

    public static function updateSLAValueOnly($issueId, $SLAId, $value) {
        $query = "update yongo_issue_sla set `value` = ? where yongo_issue_id = ? and help_sla_id = ? limit 1";
        $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
        $stmt->bind_param("iii", $value, $issueId, $SLAId);
        $stmt->execute();
    }
}