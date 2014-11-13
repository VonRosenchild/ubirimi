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

namespace Ubirimi\Yongo\Controller\Issue\LogWork;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\WorkLog;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {

        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;
        }

        $issueId = $request->request->get('issue_id');
        $projectId = $request->request->get('project_id');

        $workLogs = $this->getRepository(WorkLog::class)->getByIssueId($issueId);

        $hasEditOwnWorklogsPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_EDIT_OWN_WORKLOGS, $session->get('user/id'));
        $hasEditAllWorklogsPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_EDIT_ALL_WORKLOGS, $session->get('user/id'));

        $hasDeleteOwnWorklogsPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_DELETE_OWN_WORKLOGS, $session->get('user/id'));
        $hasDeleteAllWorklogsPermission = $this->getRepository(YongoProject::class)->userHasPermission($projectId, Permission::PERM_DELETE_ALL_WORKLOGS, $session->get('user/id'));

        return $this->render(__DIR__ . '/../../../Resources/views/issue/log_work/View.php', get_defined_vars());
    }
}
