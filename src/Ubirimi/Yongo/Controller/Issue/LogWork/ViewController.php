<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueWorkLog;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\Project;

    if (Util::checkUserIsLoggedIn()) {

    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $loggedInUserId = null;
    }

    $issueId = $_POST['issue_id'];
    $projectId = $_POST['project_id'];

    $workLogs = IssueWorkLog::getWorkLogByIssueId($issueId);

    $hasEditOwnWorklogsPermission = Project::userHasPermission($projectId, Permission::PERM_EDIT_OWN_WORKLOGS, $loggedInUserId);
    $hasEditAllWorklogsPermission = Project::userHasPermission($projectId, Permission::PERM_EDIT_ALL_WORKLOGS, $loggedInUserId);

    $hasDeleteOwnWorklogsPermission = Project::userHasPermission($projectId, Permission::PERM_DELETE_OWN_WORKLOGS, $loggedInUserId);
    $hasDeleteAllWorklogsPermission = Project::userHasPermission($projectId, Permission::PERM_DELETE_ALL_WORKLOGS, $loggedInUserId);

    if ($workLogs) {
        echo '<table class="table table-hover table-condensed">';
            echo '<tbody>';
                while ($workLogs && $workLog = $workLogs->fetch_array(MYSQLI_ASSOC)) {
                    $editedHTML = '';
                    if ($workLog['edited_flag'] == 1)
                        $editedHTML = ' - edited';
                    echo '<tr>';
                        echo '<td>';
                            echo '<div>' . $workLog['first_name'] . ' ' . $workLog['last_name'] . ' logged work - ' . $workLog['date_started'] . $editedHTML . '</div>';
                            echo '<div>Time Spent: ' . $workLog['time_spent'] . '</div>';
                            if ($workLog['comment']) {
                                echo '<div>' . $workLog['comment'] . '</div>';
                            }
                        echo '</td>';
                        echo '<td width="20px" align="right">';
                            if (($workLog['user_id'] == $loggedInUserId && $hasEditOwnWorklogsPermission) || $hasEditAllWorklogsPermission) {
                                echo '<img style="cursor: pointer" id="edit_work_log_' . $workLog['id'] . '" title="Edit" height="16px" src="/img/edit.png" />';
                            }
                        echo '</td>';
                        echo '<td width="20px" align="right">';
                            if (($workLog['user_id'] == $loggedInUserId && $hasDeleteOwnWorklogsPermission) || $hasDeleteAllWorklogsPermission) {
                                echo '<img style="cursor: pointer" id="delete_work_log_' . $workLog['id'] . '" title="Delete" height="16px" src="/img/delete.png" />';
                            }
                        echo '</td>';
                        echo '</td>';
                    echo '</tr>';
                }
            echo '</tbody>';
        echo '</table>';
    } else {
        echo '<div>No work has yet been logged on this issue.</div>';
    }