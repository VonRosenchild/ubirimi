<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
    use Ubirimi\Repository\HelpDesk\Sla;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Filter;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\Project;

    if (Util::checkUserIsLoggedIn()) {
        $issuesPerPage = $session->get('user/issues_per_page');
        $clientSettings = $session->get('client/settings');
    } else {
        $issuesPerPage = 25;
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $loggedInUserId = null;
        $clientSettings = Client::getSettings($clientId);
    }

    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Search';

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);
    $selectedProductId = $session->get('selected_product_id');
    $cliMode = false;

    $projectsForBrowsing = Client::getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS);

    $searchParameters = array();
    $parseURLData = null;
    $projectIds = null;

    if ($projectsForBrowsing) {
        $projectIdsAndNames = Util::getAsArray($projectsForBrowsing, array('id', 'name'));
        $projectsForBrowsing->data_seek(0);
        $projectIds = Util::getAsArray($projectsForBrowsing, array('id'));

        $searchCriteria = UbirimiContainer::getRepository('yongo.issue.issue')->getSearchParameters($projectsForBrowsing, $clientId);
        $issuesResult = null;

        $SLAs = Sla::getByProjectIds($projectIds);
    }

    if (isset($_POST['search'])) {

        $searchParameters = Issue::prepareDataForSearchFromPostGet($projectIds, $_POST, $_GET);

        $redirectLink = str_replace("%7C", "|", http_build_query($searchParameters));
        header('Location: /yongo/issue/search?' . $redirectLink);

        exit;
    } else {
        $getFilter = isset($_GET['filter']) ? $_GET['filter'] : null;
        $currentSearchPage = isset($_GET['page']) ? $_GET['page'] : 1;

        $getSearchParameters = Issue::prepareDataForSearchFromURL($_GET, $issuesPerPage);

        // check to see if the project Ids are all belonging to the client
        $getProjectIds = isset($_GET['project']) ? explode('|', $_GET['project']) : null;

        if ($getProjectIds) {
            if (!Project::checkProjectsBelongToClient($clientId, $getProjectIds)) {
                header('Location: /general-settings/bad-link-access-denied');
                die();
            }
        }

        $parseURLData = parse_url($_SERVER['REQUEST_URI']);

        if (isset($parseURLData['query']) && $projectsForBrowsing) {
            if (Util::searchQueryNotEmpty($getSearchParameters)) {

                $issuesResult = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($getSearchParameters, $loggedInUserId, null, $loggedInUserId);

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

    $hasGlobalBulkPermission = User::hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_BULK_CHANGE);
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

    require_once __DIR__ . '/../../Resources/views/issue/search/Search.php';