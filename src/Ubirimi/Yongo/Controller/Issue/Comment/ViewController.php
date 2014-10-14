<?php

namespace Ubirimi\Yongo\Controller\Issue\Comment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;

use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Comment;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $Id = $request->request->get('id');
        $loggedInUserId = $session->get('user/id');

        if (false === Util::checkUserIsLoggedIn()) {
            $loggedInUserId = null;
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
            $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);
        } else {
            $clientSettings = $session->get('client/settings');
        }

        $projectData = $this->getRepository('yongo.project.project')->getByIssueId($Id);
        $comments = $this->getRepository('yongo.issue.comment')->getByIssueId($Id);

        $hasAddCommentsPermission = $this->getRepository('yongo.project.project')->userHasPermission($projectData['id'], Permission::PERM_ADD_COMMENTS, $loggedInUserId);
        $hasDeleteAllComments = $this->getRepository('yongo.project.project')->userHasPermission($projectData['id'], Permission::PERM_DELETE_ALL_COMMENTS, $loggedInUserId);
        $hasDeleteOwnComments = $this->getRepository('yongo.project.project')->userHasPermission($projectData['id'], Permission::PERM_DELETE_OWN_COMMENTS, $loggedInUserId);
        $hasEditAllComments = $this->getRepository('yongo.project.project')->userHasPermission($projectData['id'], Permission::PERM_EDIT_ALL_COMMENTS, $loggedInUserId);
        $hasEditOwnComments = $this->getRepository('yongo.project.project')->userHasPermission($projectData['id'], Permission::PERM_EDIT_OWN_COMMENTS, $loggedInUserId);

        $actionButtonsFlag = true;

        return $this->render(__DIR__ . '/../../../Resources/views/issue/comment/View.php', get_defined_vars());
    }
}