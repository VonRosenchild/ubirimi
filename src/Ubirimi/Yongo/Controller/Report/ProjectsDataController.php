<?php

namespace Ubirimi\Yongo\Controller\Report;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Client;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;

class ProjectsDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        Util::checkUserIsLoggedInAndRedirect();
        $projectIdArray = $request->request->get('project_id_arr');

        if (count($projectIdArray) == 1 && $projectIdArray[0] == -1) {
            $projectsForBrowsing = Client::getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS);
            $projectIdArray = Util::getAsArray($projectsForBrowsing, array('id'));
        }

        $allIssueTypes = Project::getAllIssueTypesForProjects($projectIdArray);
        $issueTypeArray = array();
        while ($issueType = $allIssueTypes->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($issueTypeArray); $i++) {
                if ($issueTypeArray[$i]['name'] == $issueType['name']) {
                    if (!in_array($issueType['id'], explode("#", $issueTypeArray[$i]['id']))) {
                        $issueTypeArray[$i]['id'] .= '#' . $issueType['id'];
                    }
                    $found = true;
                    break;

                }
            }
            if (!$found)
                $issueTypeArray[] = $issueType;
        }

        $allIssueStatuses = Client::getAllIssueSettings('status', $clientId);
        $issueStatusArray = array();
        while ($issueStatus = $allIssueStatuses->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($issueStatusArray); $i++) {
                if ($issueStatusArray[$i]['name'] == $issueStatus['name']) {
                    if (!in_array($issueStatus['id'], explode("#", $issueStatusArray[$i]['id']))) {
                        $issueStatusArray[$i]['id'] .= '#' . $issueStatus['id'];
                    }
                    $found = true;
                    break;
                }
            }
            if (!$found)
                $issueStatusArray[] = $issueStatus;
        }

        $allIssuePriorities = Client::getAllIssueSettings('priority', $clientId);
        $issuePriorityArray = array();
        while ($issuePriority = $allIssuePriorities->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($issuePriorityArray); $i++) {
                if ($issuePriorityArray[$i]['name'] == $issuePriority['name']) {
                    if (!in_array($issuePriority['id'], explode("#", $issuePriorityArray[$i]['id']))) {
                        $issuePriorityArray[$i]['id'] .= '#' . $issuePriority['id'];
                    }
                    $found = true;
                    break;
                }
            }
            if (!$found)
                $issuePriorityArray[] = $issuePriority;
        }

        $allIssueResolutions = Client::getAllIssueSettings('resolution', $clientId);
        $issueResolutionArray = array();
        while ($issueResolution = $allIssueResolutions->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($issueResolutionArray); $i++) {
                if ($issueResolutionArray[$i]['name'] == $issueResolution['name']) {
                    if (!in_array($issueResolution['id'], explode("#", $issueResolutionArray[$i]['id']))) {
                        $issueResolutionArray[$i]['id'] .= '#' . $issueResolution['id'];
                    }
                    $found = true;
                    break;
                }
            }
            if (!$found)
                $issueResolutionArray[] = $issueResolution;
        }

        $assignableUsers = Project::getUsersWithPermission($projectIdArray, Permission::PERM_ASSIGNABLE_USER);
        $allowUnassignedIssuesFlag = Client::getYongoSetting($clientId, 'allow_unassigned_issues_flag');

        $issueUsersAssignableArray = array();
        if ($allowUnassignedIssuesFlag) {
            $issueUsersAssignableArray[]['user_id'] = -1;
            $issueUsersAssignableArray[]['first_name'] = 'No one';
            $issueUsersAssignableArray[]['last_name'] = '';
        }
        while ($assignableUsers && $issueUser = $assignableUsers->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($issueUsersAssignableArray); $i++) {
                if ($issueUsersAssignableArray[$i]['first_name'] == $issueUser['first_name'] && $issueUsersAssignableArray[$i]['last_name'] == $issueUser['last_name']) {
                    $issueUsersAssignableArray[$i]['user_id'] .= '#' . $issueUser['user_id'];
                    $found = true;
                    break;
                }
            }
            if (!$found)
                $issueUsersAssignableArray[] = $issueUser;
        }

        // components are releases
        $projectComponents = Project::getComponents($projectIdArray);

        $projectComponentsArray = array();
        while ($projectComponents && $projectComponent = $projectComponents->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($projectComponentsArray); $i++) {
                if ($projectComponentsArray[$i]['name'] == $projectComponent['name']) {
                    if (!in_array($projectComponent['id'], explode("#", $projectComponentsArray[$i]['id']))) {
                        $projectComponentsArray[$i]['id'] .= '#' . $projectComponent['id'];
                    }
                    $found = true;
                    break;
                }
            }
            if (!$found)
                $projectComponentsArray[] = $projectComponent;
        }

        $allProjectVersions = Project::getVersions($projectIdArray);
        $projectVersionsArray = array();
        while ($allProjectVersions && $projectVersion = $allProjectVersions->fetch_array(MYSQLI_ASSOC)) {
            $found = false;
            for ($i = 0; $i < count($projectVersionsArray); $i++) {
                if ($projectVersionsArray[$i]['name'] == $projectVersion['name']) {
                    if (!in_array($projectVersion['id'], explode("#", $projectVersionsArray[$i]['id']))) {
                        $projectVersionsArray[$i]['id'] .= '#' . $projectVersion['id'];
                    }
                    $found = true;
                    break;
                }
            }
            if (!$found)
                $projectVersionsArray[] = $projectVersion;
        }

        $result = array(
            'type_arr' => $issueTypeArray,
            'status_arr' => $issueStatusArray,
            'priority_arr' => $issuePriorityArray,
            'resolution_arr' => $issueResolutionArray,
            'user_arr_assignable' => $issueUsersAssignableArray,
            'project_component_arr' => $projectComponentsArray,
            'project_version_arr' => $projectVersionsArray
        );

        return new Response(json_encode($result));
    }
}
