<?php

namespace Ubirimi\Agile\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Attachment;
use Ubirimi\Yongo\Repository\Issue\Comment;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Component;
use Ubirimi\Yongo\Repository\Issue\Version;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;

class IssueDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientSettings = Client::getSettings($session->get('client/id'));
        $issueId = $request->request->get('id');
        $close = $request->request->get('close', 0);
        $issueParameters = array('issue_id' => $issueId);

        $issue = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($issueParameters, $session->get('user/id'));

        $projectId = $issue['issue_project_id'];
        $issueProject = Project::getById($projectId);

        $comments = UbirimiContainer::getRepository('yongo.issue.comment')->getByIssueId($issueId, 'desc');
        $components = Component::getByIssueIdAndProjectId($issueId, $projectId);

        $versionsAffected = Version::getByIssueIdAndProjectId(
            $issueId,
            $projectId,
            Issue::ISSUE_AFFECTED_VERSION_FLAG
        );

        $versionsTargeted = Version::getByIssueIdAndProjectId(
            $issueId,
            $projectId,
            Issue::ISSUE_FIX_VERSION_FLAG
        );

        $hasAddCommentsPermission = Project::userHasPermission(
            $projectId,
            Permission::PERM_ADD_COMMENTS,
            $session->get('user/id')
        );

        $hasDeleteAllComments = Project::userHasPermission(
            $projectId,
            Permission::PERM_DELETE_ALL_COMMENTS,
            $session->get('user/id')
        );

        $hasDeleteOwnComments = Project::userHasPermission(
            $projectId,
            Permission::PERM_DELETE_OWN_COMMENTS,
            $session->get('user/id')
        );

        $hasEditAllComments = Project::userHasPermission(
            $projectId,
            Permission::PERM_EDIT_ALL_COMMENTS,
            $session->get('user/id')
        );

        $hasEditOwnComments = Project::userHasPermission(
            $projectId,
            Permission::PERM_EDIT_OWN_COMMENTS,
            $session->get('user/id')
        );

        $attachments = UbirimiContainer::getRepository('yongo.issue.attachment')->getByIssueId($issue['id']);
        if ($attachments && $attachments->num_rows) {
            $hasDeleteOwnAttachmentsPermission = Project::userHasPermission(
                $projectId,
                Permission::PERM_DELETE_OWN_ATTACHMENTS,
                $session->get('user/id')
            );

            $hasDeleteAllAttachmentsPermission = Project::userHasPermission(
                $projectId,
                Permission::PERM_DELETE_OWN_ATTACHMENTS,
                $session->get('user/id')
            );
        }
        $childrenIssues = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters(array('parent_id' => $issueId), $session->get('user/id'));

        return $this->render(__DIR__ . '/../Resources/views/IssueData.php', get_defined_vars());
    }
}
