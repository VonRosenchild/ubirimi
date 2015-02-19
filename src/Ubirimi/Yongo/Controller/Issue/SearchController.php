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

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class SearchController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $issuesPerPage = $session->get('user/issues_per_page');
            $clientSettings = $session->get('client/settings');
        } else {
            $issuesPerPage = 25;
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
        }

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Search';

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);
        $selectedProductId = $session->get('selected_product_id');
        $cliMode = false;

        $projectsForBrowsing = $this->getRepository(UbirimiClient::class)->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS);

        $searchParameters = array();
        $parseURLData = null;
        $projectIds = null;

        if ($projectsForBrowsing) {
            $projectIdsAndNames = Util::getAsArray($projectsForBrowsing, array('id', 'name'));
            $projectsForBrowsing->data_seek(0);
            $projectIds = Util::getAsArray($projectsForBrowsing, array('id'));

            $searchCriteria = $this->getRepository(Issue::class)->getSearchParameters($projectsForBrowsing, $clientId);
            $issuesResult = null;

            $SLAs = UbirimiContainer::get()['repository']->get(Sla::class)->getByProjectIds($projectIds);
        }

        if ($request->request->has('search')) {

            $searchParameters = $this->getRepository(Issue::class)->prepareDataForSearchFromPostGet($projectIds, $_POST, $_GET);

            $redirectLink = str_replace("%7C", "|", http_build_query($searchParameters));
            return new RedirectResponse('/yongo/issue/search?' . $redirectLink);
        } else {
            $getFilter = $request->get('filter');
            $currentSearchPage = $request->get('page');
            $currentSearchPage = isset($currentSearchPage) ? $currentSearchPage : 1;

            $getSearchParameters = $this->getRepository(Issue::class)->prepareDataForSearchFromURL($_GET, $issuesPerPage);

            // check to see if the project Ids are all belonging to the client
            $getProjectIds = isset($_GET['project']) ? explode('|', $_GET['project']) : null;

            if ($getProjectIds && !(count($getProjectIds) == 1 && $getProjectIds[0] == -1)) {
                if (!$this->getRepository(YongoProject::class)->checkProjectsBelongToClient($clientId, $getProjectIds)) {
                    return new RedirectResponse('/general-settings/bad-link-access-denied');
                }
            }

            $parseURLData = parse_url($_SERVER['REQUEST_URI']);

            if (isset($parseURLData['query']) && $projectsForBrowsing) {
                if (Util::searchQueryNotEmpty($getSearchParameters)) {

                    $issuesResult = $this->getRepository(Issue::class)->getByParameters($getSearchParameters, $loggedInUserId, null, $loggedInUserId);

                    $issues = $issuesResult[0];
                    $issuesCount = $issuesResult[1];
                    $countPages = ceil($issuesCount / $issuesPerPage);
                    $getSearchParameters['count_pages'] = $countPages;
                    $getSearchParameters['link_to_page'] = '/yongo/issue/search';
                }
            }
        }

        $columns = array('code', 'summary', 'priority', 'status', 'created', 'updated', 'reporter', 'assignee', 'settings_menu');
        if (Util::checkUserIsLoggedIn()) {
            $columns = explode('#', $session->get('user/issues_display_columns'));

            $columns[] = 'settings_menu';
            $columns[] = '';
        }

        $hasGlobalBulkPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_BULK_CHANGE);
        $customFilters = $this->getRepository(IssueFilter::class)->getAllByUser($loggedInUserId);

        if ($getFilter) {
            $menuSelectedCategory = 'filters';
        } else {
            $menuSelectedCategory = 'issue';
        }

        $extraParameters = array();
        if ($getFilter) {
            $extraParameters[] = 'filter=' . $getFilter;
        }

        $extraParametersURL = '';
        if (count($extraParameters)) {
            $extraParametersURL = implode('&', $extraParameters);
        }

        $urlIssuePrefix = '/yongo/issue/';

        $parseData = parse_url($_SERVER['REQUEST_URI']);
        $query = isset($parseData['query']) ? $parseData['query'] : '';

        if (isset($query) && $query != '') {
            $session->set('last_search_parameters', $parseData['query']);
        } else {
            $session->remove('last_search_parameters');
        }

        return $this->render(__DIR__ . '/../../Resources/views/issue/search/Search.php', get_defined_vars());
    }
}