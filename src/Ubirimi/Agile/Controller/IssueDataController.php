<?php

namespace Ubirimi\Agile\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;

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

        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($session->get('client/id'));
        $issueId = $request->request->get('id');
        $close = $request->request->get('close', 0);
        $issueParameters = array('issue_id' => $issueId);

        $issue = $this->getRepository('yongo.issue.issue')->getByParameters($issueParameters, $session->get('user/id'));

        $projectId = $issue['issue_project_id'];
        $issueProject = $this->getRepository('yongo.project.project')->getById($projectId);

        $comments = $this->getRepository('yongo.issue.comment')->getByIssueId($issueId, 'desc');
        $components = $this->getRepository('yongo.issue.component')->getByIssueIdAndProjectId($issueId, $projectId);

        $versionsAffected = $this->getRepository('yongo.issue.version')->getByIssueIdAndProjectId(
            $issueId,
            $projectId,
            Issue::ISSUE_AFFECTED_VERSION_FLAG
        );

        $versionsTargeted = $this->getRepository('yongo.issue.version')->getByIssueIdAndProjectId(
            $issueId,
            $projectId,
            Issue::ISSUE_FIX_VERSION_FLAG
        );

        $hasAddCommentsPermission = $this->getRepository('yongo.project.project')->userHasPermission(
            $projectId,
            Permission::PERM_ADD_COMMENTS,
            $session->get('user/id')
        );

        $hasDeleteAllComments = $this->getRepository('yongo.project.project')->userHasPermission(
            $projectId,
            Permission::PERM_DELETE_ALL_COMMENTS,
            $session->get('user/id')
        );

        $hasDeleteOwnComments = $this->getRepository('yongo.project.project')->userHasPermission(
            $projectId,
            Permission::PERM_DELETE_OWN_COMMENTS,
            $session->get('user/id')
        );

        $hasEditAllComments = $this->getRepository('yongo.project.project')->userHasPermission(
            $projectId,
            Permission::PERM_EDIT_ALL_COMMENTS,
            $session->get('user/id')
        );

        $hasEditOwnComments = $this->getRepository('yongo.project.project')->userHasPermission(
            $projectId,
            Permission::PERM_EDIT_OWN_COMMENTS,
            $session->get('user/id')
        );

        $attachments = $this->getRepository('yongo.issue.attachment')->getByIssueId($issue['id']);
        if ($attachments && $attachments->num_rows) {
            $hasDeleteOwnAttachmentsPermission = $this->getRepository('yongo.project.project')->userHasPermission(
                $projectId,
                Permission::PERM_DELETE_OWN_ATTACHMENTS,
                $session->get('user/id')
            );

            $hasDeleteAllAttachmentsPermission = $this->getRepository('yongo.project.project')->userHasPermission(
                $projectId,
                Permission::PERM_DELETE_OWN_ATTACHMENTS,
                $session->get('user/id')
            );
        }
        $childrenIssues = $this->getRepository('yongo.issue.issue')->getByParameters(array('parent_id' => $issueId), $session->get('user/id'));

        return $this->render(__DIR__ . '/../Resources/views/IssueData.php', get_defined_vars());
    }
}
