<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Filter;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;

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
            $clientId = $this->getRepository('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;
            $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);
        }

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Search';

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);
        $selectedProductId = $session->get('selected_product_id');
        $cliMode = false;

        $projectsForBrowsing = $this->getRepository('ubirimi.general.client')->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS);

        $searchParameters = array();
        $parseURLData = null;
        $projectIds = null;

        if ($projectsForBrowsing) {
            $projectIdsAndNames = Util::getAsArray($projectsForBrowsing, array('id', 'name'));
            $projectsForBrowsing->data_seek(0);
            $projectIds = Util::getAsArray($projectsForBrowsing, array('id'));

            $searchCriteria = $this->getRepository('yongo.issue.issue')->getSearchParameters($projectsForBrowsing, $clientId);
            $issuesResult = null;

            $SLAs = Sla::getByProjectIds($projectIds);
        }

        if (isset($_POST['search'])) {

            $searchParameters = Issue::prepareDataForSearchFromPostGet($projectIds, $_POST, $_GET);

            $redirectLink = str_replace("%7C", "|", http_build_query($searchParameters));
            header('Location: /yongo/issue/search?' . $redirectLink);

            exit;
        } else {
            $getFilter = $request->get('filter');
            $currentSearchPage = $request->get('page');

            $getSearchParameters = Issue::prepareDataForSearchFromURL($_GET, $issuesPerPage);

            // check to see if the project Ids are all belonging to the client
            $getProjectIds = isset($_GET['project']) ? explode('|', $_GET['project']) : null;

            if ($getProjectIds) {
                if (!$this->getRepository('yongo.project.project')->checkProjectsBelongToClient($clientId, $getProjectIds)) {
                    header('Location: /general-settings/bad-link-access-denied');
                    die();
                }
            }

            $parseURLData = parse_url($_SERVER['REQUEST_URI']);

            if (isset($parseURLData['query']) && $projectsForBrowsing) {
                if (Util::searchQueryNotEmpty($getSearchParameters)) {

                    $issuesResult = $this->getRepository('yongo.issue.issue')->getByParameters($getSearchParameters, $loggedInUserId, null, $loggedInUserId);

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

        $hasGlobalBulkPermission = $this->getRepository('ubirimi.user.user')->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_BULK_CHANGE);
        $customFilters = Filter::getAllByUser($loggedInUserId);

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