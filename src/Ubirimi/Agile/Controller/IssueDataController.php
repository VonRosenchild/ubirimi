<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueAttachment;
    use Ubirimi\Yongo\Repository\Issue\IssueComment;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueComponent;
    use Ubirimi\Yongo\Repository\Issue\IssueVersion;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();
    $issueId = $_POST['id'];
    $close = isset($_POST['close']) ? $_POST['close'] : 0;
    $issueParameters = array('issue_id' => $issueId);

    $issue = Issue::getByParameters($issueParameters, $loggedInUserId);

    $projectId = $issue['issue_project_id'];
    $issueProject = Project::getById($projectId);

    $comments = IssueComment::getByIssueId($issueId, 'desc');
    $components = IssueComponent::getByIssueIdAndProjectId($issueId, $projectId);

    $versionsAffected = IssueVersion::getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_AFFECTED_VERSION_FLAG);
    $versionsTargeted = IssueVersion::getByIssueIdAndProjectId($issueId, $projectId, Issue::ISSUE_FIX_VERSION_FLAG);

    $hasAddCommentsPermission = Project::userHasPermission($projectId, Permission::PERM_ADD_COMMENTS, $loggedInUserId);
    $hasDeleteAllComments = Project::userHasPermission($projectId, Permission::PERM_DELETE_ALL_COMMENTS, $loggedInUserId);
    $hasDeleteOwnComments = Project::userHasPermission($projectId, Permission::PERM_DELETE_OWN_COMMENTS, $loggedInUserId);

    $hasEditAllComments = Project::userHasPermission($projectId, Permission::PERM_EDIT_ALL_COMMENTS, $loggedInUserId);
    $hasEditOwnComments = Project::userHasPermission($projectId, Permission::PERM_EDIT_OWN_COMMENTS, $loggedInUserId);

    $attachments = IssueAttachment::getByIssueId($issue['id']);
    if ($attachments && $attachments->num_rows) {
        $hasDeleteOwnAttachmentsPermission = Project::userHasPermission($projectId, Permission::PERM_DELETE_OWN_ATTACHMENTS, $loggedInUserId);
        $hasDeleteAllAttachmentsPermission = Project::userHasPermission($projectId, Permission::PERM_DELETE_OWN_ATTACHMENTS, $loggedInUserId);
    }
    $childrenIssues = Issue::getByParameters(array('parent_id' => $issueId), $loggedInUserId);

    require_once __DIR__ . '/../Resources/views/IssueData.php';