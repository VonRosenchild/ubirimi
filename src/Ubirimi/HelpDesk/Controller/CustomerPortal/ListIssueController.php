<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'home';

    $projectsForBrowsing = Client::getProjects($clientId, null, null, true);

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_HELP_DESK);

    if ($projectsForBrowsing) {
        $projectIdsAndNames = Util::getAsArray($projectsForBrowsing, array('id', 'name'));
        $projectsForBrowsing->data_seek(0);
        $projectIds = Util::getAsArray($projectsForBrowsing, array('id'));

        $searchCriteria = Issue::getSearchParameters($projectsForBrowsing, $clientId, 1);
        $issuesResult = null;
    }

    if (isset($_POST['search'])) {

        $searchParameters = Issue::prepareDataForSearchFromPostGet($projectIds, $_POST, $_GET);

        $redirectLink = str_replace("%7C", "|", http_build_query($searchParameters));
        header('Location: /helpdesk/customer-portal/tickets?' . $redirectLink);

        exit;
    } else {
        $getSearchParameters = Issue::prepareDataForSearchFromURL($_GET, 30);
        $getSearchParameters['helpdesk_flag'] = 1;
        // check to see if the project Ids are all belonging to the client
        $getProjectIds = isset($_GET['project']) ? explode('|', $_GET['project']) : null;
        if ($getProjectIds) {
            for ($pos = 0; $pos < count($getProjectIds); $pos++) {
                $projectFilter = Project::getById($getProjectIds[$pos]);

                if ($projectFilter['client_id'] != $clientId) {
                    header('Location: /general-settings/bad-link-access-denied');
                    die();
                }
            }
        }

        $parseURLData = parse_url($_SERVER['REQUEST_URI']);
        $projectsForBrowsing = array(229);
        if (isset($parseURLData['query']) && $projectsForBrowsing) {
            if (Util::searchQueryNotEmpty($getSearchParameters)) {
                $issuesResult = Issue::getByParameters($getSearchParameters, $loggedInUserId);

                $issues = $issuesResult[0];
                $issuesCount = $issuesResult[1];
                $countPages = ceil($issuesCount / 30);
                $getSearchParameters['count_pages'] = $countPages;
                $getSearchParameters['link_to_page'] = '/helpdesk/customer-portal/tickets';
            }
        }
    }

    $SLAs = SLA::getByProjectIds(array(229));
    $columns = array('code', 'summary', 'priority', 'status', 'created', 'updated', 'reporter', 'assignee', 'settings_menu');
    if (Util::checkUserIsLoggedIn()) {
        $columns = explode('#', $session->get('user/issues_display_columns'));

        $columns[] = 'settings_menu';
        $columns[] = '';
    }

    require_once __DIR__ . '/../../Resources/views/customer_portal/ListIssue.php';