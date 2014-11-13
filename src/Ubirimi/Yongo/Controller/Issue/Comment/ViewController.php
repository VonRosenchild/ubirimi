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

namespace Ubirimi\Yongo\Controller\Issue\Comment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueComment;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $Id = $request->request->get('id');
        $loggedInUserId = $session->get('user/id');

        if (false === Util::checkUserIsLoggedIn()) {
            $loggedInUserId = null;
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
        } else {
            $clientSettings = $session->get('client/settings');
        }

        $projectData = $this->getRepository(YongoProject::class)->getByIssueId($Id);
        $comments = $this->getRepository(IssueComment::class)->getByIssueId($Id);

        $hasAddCommentsPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectData['id'], Permission::PERM_ADD_COMMENTS, $loggedInUserId);
        $hasDeleteAllComments = $this->getRepository(YongoProject::class)->userHasPermission($projectData['id'], Permission::PERM_DELETE_ALL_COMMENTS, $loggedInUserId);
        $hasDeleteOwnComments = $this->getRepository(YongoProject::class)->userHasPermission($projectData['id'], Permission::PERM_DELETE_OWN_COMMENTS, $loggedInUserId);
        $hasEditAllComments = $this->getRepository(YongoProject::class)->userHasPermission($projectData['id'], Permission::PERM_EDIT_ALL_COMMENTS, $loggedInUserId);
        $hasEditOwnComments = $this->getRepository(YongoProject::class)->userHasPermission($projectData['id'], Permission::PERM_EDIT_OWN_COMMENTS, $loggedInUserId);

        $actionButtonsFlag = true;

        return $this->render(__DIR__ . '/../../../Resources/views/issue/comment/View.php', get_defined_vars());
    }
}