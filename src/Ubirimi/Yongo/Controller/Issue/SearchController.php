<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueFilter;
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

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);
    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Search';

    $projectsForBrowsing = Client::getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS);

    $searchParameters = array();
    $parseURLData = null;
    $projectIds = null;

    if ($projectsForBrowsing) {
        $projectIdsAndNames = Util::getAsArray($projectsForBrowsing, array('id', 'name'));
        $projectsForBrowsing->data_seek(0);
        $projectIds = Util::getAsArray($projectsForBrowsing, array('id'));

        $searchCriteria = Issue::getSearchParameters($projectsForBrowsing, $clientId);
        $issuesResult = null;

        $SLAs = SLA::getByProjectIds($projectIds);
    }

    if (isset($_POST['search'])) {

        $searchParameters = Issue::prepareDataForSearchFromPostGet($projectIds, $_POST, $_GET);

        $redirectLink = str_replace("%7C", "|", http_build_query($searchParameters));
        header('Location: /yongo/issue/search?' . $redirectLink);

        exit;
    } else {
        $getFilter = isset($_GET['filter']) ? $_GET['filter'] : null;

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

                $issuesResult = Issue::getByParameters($getSearchParameters, $loggedInUserId, null, $loggedInUserId);

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
    $customFilters = IssueFilter::getAllByUser($loggedInUserId);

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

    require_once __DIR__ . '/../../Resources/views/issue/search/Search.php';