<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Project\Project;

    $issuesPerPage = $session->get('user/issues_per_page');

    $searchParameters = array();
    $parseURLData = null;

    $getFilter = isset($_GET['filter']) ? $_GET['filter'] : null;
    $getPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $getSortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'created';
    $getSortOrder = isset($_GET['order']) ? $_GET['order'] : 'desc';
    $getSearchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : null;
    $getSummaryFlag = isset($_GET['summary_flag']) ? $_GET['summary_flag'] : null;
    $getDescriptionFlag = isset($_GET['description_flag']) ? $_GET['description_flag'] : null;
    $getCommentsFlag = isset($_GET['comments_flag']) ? $_GET['comments_flag'] : null;
    $getProjectIds = isset($_GET['project']) ? explode('|', $_GET['project']) : null;

    if ($getProjectIds) {
        $projectsData = Project::getByIds($getProjectIds);
        if (!$projectsData) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }
        while ($projectsData && $data = $projectsData->fetch_array(MYSQLI_ASSOC)) {

            if (Util::checkUserIsLoggedIn()) {
                if ($data['client_id'] != $clientId) {
                    header('Location: /general-settings/bad-link-access-denied');
                    die();
                }
            } else {
                $hasBrowsingPermission = Project::userHasPermission(array($data['id']), Permission::PERM_BROWSE_PROJECTS);
                if (!$hasBrowsingPermission) {
                    header('Location: /general-settings/bad-link-access-denied');
                    die();
                }
            }
        }
    }

    $getAssigneeIds = isset($_GET['assignee']) ? explode('|', $_GET['assignee']) : null;
    $getReportedIds = isset($_GET['reporter']) ? explode('|', $_GET['reporter']) : null;
    $getIssueTypeIds = isset($_GET['type']) ? explode('|', $_GET['type']) : null;
    $getIssueStatusIds = isset($_GET['status']) ? explode('|', $_GET['status']) : null;
    $getIssuePriorityIds = isset($_GET['priority']) ? explode('|', $_GET['priority']) : null;
    $getProjectComponentIds = isset($_GET['component']) ? explode('|', $_GET['component']) : null;
    $getProjectVersionIds = isset($_GET['version']) ? explode('|', $_GET['version']) : null;
    $getIssueResolutionIds = isset($_GET['resolution']) ? explode('|', $_GET['resolution']) : null;

    $getSearchParameters = array('search_query' => $getSearchQuery,
                                 'summary_flag' => $getSummaryFlag,
                                 'description_flag' => $getDescriptionFlag,
                                 'comments_flag' => $getCommentsFlag,
                                 'project' => $getProjectIds,
                                 'assignee' => $getAssigneeIds,
                                 'reporter' => $getReportedIds,
                                 'filter' => $getFilter,
                                 'type' => $getIssueTypeIds,
                                 'status' => $getIssueStatusIds,
                                 'priority' => $getIssuePriorityIds,
                                 'component' => $getProjectComponentIds,
                                 'version' => $getProjectVersionIds,
                                 'resolution' => $getIssueResolutionIds,
                                 'sort' => $getSortColumn,
                                 'sort_order' => $getSortOrder);

    $parseURLData = parse_url($_SERVER['REQUEST_URI']);

    if (isset($parseURLData['query'])) {
        if (Util::searchQueryNotEmpty($getSearchParameters)) {

            $issues = Issue::getByParameters($getSearchParameters, $loggedInUserId, null, $loggedInUserId);
            $issuesCount = $issues->num_rows;
            $getSearchParameters['link_to_page'] = '/yongo/issue/printable-list';
        }
    }

    $columns = array('code',
                     'summary',
                     'priority',
                     'status',
                     'created',
                     'updated',
                     'reporter',
                     'assignee');

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Print List';
    $menuSelectedCategory = null;
    require_once __DIR__ . '/../../Resources/views/issue/search/SearchListPrintable.php';