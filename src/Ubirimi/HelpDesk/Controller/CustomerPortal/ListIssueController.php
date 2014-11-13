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

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ListIssueController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'home';
        $clientId = $session->get('client/id');
        $projectsForBrowsing = $this->getRepository(UbirimiClient::class)->getProjects($clientId, null, null, true);
        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_HELP_DESK);
        $selectedProductId = $session->get('selected_product_id');

        $cliMode = false;

        if ($projectsForBrowsing) {
            $projectIdsAndNames = Util::getAsArray($projectsForBrowsing, array('id', 'name'));
            $projectsForBrowsing->data_seek(0);
            $projectIds = Util::getAsArray($projectsForBrowsing, array('id'));

            $searchCriteria = $this->getRepository(Issue::class)->getSearchParameters($projectsForBrowsing, $session->get('client/id'), 1);
            $issuesResult = null;
        }

        if ($request->request->has('search')) {
            $searchParameters = $this->getRepository(Issue::class)->prepareDataForSearchFromPostGet($projectIds, $request->request->all(), $request->query->all());

            $redirectLink = str_replace("%7C", "|", http_build_query($searchParameters));

            return new RedirectResponse('/helpdesk/customer-portal/tickets?' . $redirectLink);
        } else {
            $getSearchParameters = $this->getRepository(Issue::class)->prepareDataForSearchFromURL($request->query->all(), 30);
            $getSearchParameters['helpdesk_flag'] = 1;
            // check to see if the project Ids are all belonging to the client
            $getProjectIds = $request->request->has('project') ? explode('|', $request->query->get('project')) : null;
            if ($getProjectIds) {
                for ($pos = 0; $pos < count($getProjectIds); $pos++) {
                    $projectFilter = $this->getRepository(YongoProject::class)->getById($getProjectIds[$pos]);

                    if ($projectFilter['client_id'] != $session->get('client/id')) {
                        return new RedirectResponse('/general-settings/bad-link-access-denied');
                    }
                }
            }

            $parseURLData = parse_url($_SERVER['REQUEST_URI']);
            $projectsForBrowsing = array(229);
            if (isset($parseURLData['query']) && $projectsForBrowsing) {
                if (Util::searchQueryNotEmpty($getSearchParameters)) {
                    $issuesResult = $this->getRepository(Issue::class)->getByParameters($getSearchParameters, $session->get('user/id'));

                    $issues = $issuesResult[0];
                    $issuesCount = $issuesResult[1];
                    $issuesPerPage = $session->get('user/issues_per_page');
                    $currentSearchPage = isset($_GET['page']) ? $_GET['page'] : 1;
                    $countPages = ceil($issuesCount / 30);
                    $getSearchParameters['count_pages'] = $countPages;
                    $getSearchParameters['link_to_page'] = '/helpdesk/customer-portal/tickets';
                }
            }
        }

        $SLAs = $this->getRepository(Sla::class)->getByProjectIds(array(229));

        $columns = array('code', 'summary', 'priority', 'status', 'created', 'updated', 'reporter', 'assignee', 'settings_menu');
        if (Util::checkUserIsLoggedIn()) {
            $columns = explode('#', $session->get('user/issues_display_columns'));

            $columns[] = 'settings_menu';
            $columns[] = '';
        }

        $parseData = parse_url($_SERVER['REQUEST_URI']);
        $query = isset($parseData['query']) ? $parseData['query'] : '';

        if (isset($query)) {
            $session->set('last_search_parameters', $parseData['query']);
        } else {
            $session->remove('last_search_parameters');
        }

        return $this->render(__DIR__ . '/../../Resources/views/customer_portal/ListIssue.php', get_defined_vars());
    }
}
