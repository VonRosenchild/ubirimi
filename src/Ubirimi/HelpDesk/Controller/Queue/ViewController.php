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

namespace Ubirimi\HelpDesk\Controller\Queue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Queue\Queue;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');
        $issuesPerPage = $session->get('user/issues_per_page');

        $projectId = $request->get('id');
        $queueId = $request->get('queue_id');
        $page = $request->get('page', 1);

        $project = $this->getRepository(YongoProject::class)->getById($projectId);
        $queueSelected = $this->getRepository(Queue::class)->getById($queueId);

        $columns = explode('#', $queueSelected['columns']);

        $SLAs = $this->getRepository(Sla::class)->getByProjectId($projectId);
        if ($SLAs) {
            $slaSelected = $SLAs->fetch_array(MYSQLI_ASSOC);
            $SLAs->data_seek(0);
        }

        $queues = $this->getRepository(Queue::class)->getByProjectId($projectId);
        if ($queues) {
            $whereSQL = $this->getRepository(Issue::class)->prepareWhereClauseFromQueue(
                $queueSelected['definition'],
                $session->get('user/id'),
                $projectId,
                $session->get('client/id')
            );

            $whereSQL = 'issue_main_table.project_id = ' . $projectId . ' AND ' . $whereSQL;

            $getSearchParameters = array();
            $getSearchParameters['page'] = $page;
            $getSearchParameters['issues_per_page'] = 50;

            $issuesResult = $this->getRepository(Issue::class)->getByParameters(
                $getSearchParameters,
                $session->get('user/id'),
                $whereSQL,
                $session->get('user/id')
            );

            $issues = $issuesResult[0];

            $issuesCount = $issuesResult[1];
            $countPages = ceil($issuesCount / $issuesPerPage);
            $currentSearchPage = 1;
            $getSearchParameters = array();
            $getSearchParameters['page'] = $page;
            $getSearchParameters['count_pages'] = $countPages;
            $getSearchParameters['link_to_page'] = '/helpdesk/queues/' . $projectId . '/' . $queueId;
        }

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'queue';
        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Help Desks';

        $selectedProductId = $session->get('selected_product_id');
        $cliMode = false;

        return $this->render(__DIR__ . '/../../Resources/views/queue/View.php', get_defined_vars());
    }
}
