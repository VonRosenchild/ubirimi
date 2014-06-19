<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_POST['project_id'];

    $issueTypeId = $_POST['issue_type_id'];
    $sysOperationId = $_POST['operation_id'];
    $projectData = Project::getById($projectId);

    $workflowUsed = Project::getWorkflowUsedForType($projectId, $issueTypeId);

    if ($workflowUsed) {
        require_once __DIR__ . '/../../Resources/views/issue/_dialogCreate.php';

    } else {
        echo '<tr>';
            echo '<td colspan="2">';
                echo '<div class="infoBox">There is no workflow set for this issue type.</div>';
            echo '</td>';
        echo '</tr>';
    }