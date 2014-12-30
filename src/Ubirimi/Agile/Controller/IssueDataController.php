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

namespace Ubirimi\Agile\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueAttachment;
use Ubirimi\Yongo\Repository\Issue\IssueComment;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class IssueDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($session->get('client/id'));
        $issueId = $request->request->get('id');
        $close = $request->request->get('close', 0);
        $issueParameters = array('issue_id' => $issueId);

        $issue = $this->getRepository(Issue::class)->getByParameters($issueParameters, $session->get('user/id'));

        $projectId = $issue['issue_project_id'];
        $issueProject = $this->getRepository(YongoProject::class)->getById($projectId);

        $projectSubTaskIssueTypes = $this->getRepository(YongoProject::class)->getSubTasksIssueTypes($projectId, 'array', 'id');

        $comments = $this->getRepository(IssueComment::class)->getByIssueId($issueId, 'desc');
        $components = $this->getRepository(IssueComponent::class)->getByIssueIdAndProjectId($issueId, $projectId);

        $versionsAffected = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId(
            $issueId,
            $projectId,
            Issue::ISSUE_AFFECTED_VERSION_FLAG
        );

        $versionsTargeted = $this->getRepository(IssueVersion::class)->getByIssueIdAndProjectId(
            $issueId,
            $projectId,
            Issue::ISSUE_FIX_VERSION_FLAG
        );

        $hasAddCommentsPermission = $this->getRepository(YongoProject::class)->userHasPermission(
            $projectId,
            Permission::PERM_ADD_COMMENTS,
            $session->get('user/id')
        );

        $hasDeleteAllComments = $this->getRepository(YongoProject::class)->userHasPermission(
            $projectId,
            Permission::PERM_DELETE_ALL_COMMENTS,
            $session->get('user/id')
        );

        $hasDeleteOwnComments = $this->getRepository(YongoProject::class)->userHasPermission(
            $projectId,
            Permission::PERM_DELETE_OWN_COMMENTS,
            $session->get('user/id')
        );

        $hasEditAllComments = $this->getRepository(YongoProject::class)->userHasPermission(
            $projectId,
            Permission::PERM_EDIT_ALL_COMMENTS,
            $session->get('user/id')
        );

        $hasEditOwnComments = $this->getRepository(YongoProject::class)->userHasPermission(
            $projectId,
            Permission::PERM_EDIT_OWN_COMMENTS,
            $session->get('user/id')
        );

        $attachments = $this->getRepository(IssueAttachment::class)->getByIssueId($issue['id']);
        if ($attachments && $attachments->num_rows) {
            $hasDeleteOwnAttachmentsPermission = $this->getRepository(YongoProject::class)->userHasPermission(
                $projectId,
                Permission::PERM_DELETE_OWN_ATTACHMENTS,
                $session->get('user/id')
            );

            $hasDeleteAllAttachmentsPermission = $this->getRepository(YongoProject::class)->userHasPermission(
                $projectId,
                Permission::PERM_DELETE_OWN_ATTACHMENTS,
                $session->get('user/id')
            );
        }
        $childrenIssues = $this->getRepository(Issue::class)->getByParameters(array('parent_id' => $issueId), $session->get('user/id'));

        return $this->render(__DIR__ . '/../Resources/views/IssueData.php', get_defined_vars());
    }
}
