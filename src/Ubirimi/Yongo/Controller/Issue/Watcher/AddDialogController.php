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

namespace Ubirimi\Yongo\Controller\Issue\Watcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Watcher;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;


class AddDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {

        $clientId = UbirimiContainer::get()['session']->get('client/id');
        $loggedInUserId = UbirimiContainer::get()['session']->get('user/id');

        $issueId = $request->request->get('id');

        $issueData = $this->getRepository(Issue::class)->getByIdSimple($issueId);

        $watchers = $this->getRepository(Watcher::class)->getByIssueId($issueId);
        // todo: users watchers de aici trebuie sa fie useri ce au permisiune de browsing la proiectul acesta
        $users = $this->getRepository(UbirimiClient::class)->getUsers($clientId);
        $watcherArray = array();

        if ($watchers) {
            while ($watchers && $watcher = $watchers->fetch_array(MYSQLI_ASSOC)) {
                $watcherArray[] = $watcher['id'];
            }
            $watchers->data_seek(0);
        }

        $hasViewVotersAndWatchersPermission = $this->getRepository(YongoProject::class)->userHasPermission($issueData['project_id'], Permission::PERM_VIEW_VOTERS_AND_WATCHERS, $loggedInUserId);
        $hasManageWatchersPermission = $this->getRepository(YongoProject::class)->userHasPermission($issueData['project_id'], Permission::PERM_MANAGE_WATCHERS, $loggedInUserId);

        return $this->render(__DIR__ . '/../../../Resources/views/issue/watcher/AddDialog.php', get_defined_vars());

    }
}