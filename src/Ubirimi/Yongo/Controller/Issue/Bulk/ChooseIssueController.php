<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;

    Util::checkUserIsLoggedInAndRedirect();
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
    $getAssigneeIds = isset($_GET['assignee']) ? explode('|', $_GET['assignee']) : null;
    $getReportedIds = isset($_GET['reporter']) ? explode('|', $_GET['reporter']) : null;
    $getIssueTypeIds = isset($_GET['type']) ? explode('|', $_GET['type']) : null;
    $getIssueStatusIds = isset($_GET['status']) ? explode('|', $_GET['status']) : null;
    $getIssuePriorityIds = isset($_GET['priority']) ? explode('|', $_GET['priority']) : null;
    $getProjectComponentIds = isset($_GET['component']) ? explode('|', $_GET['component']) : null;
    $getProjectVersionIds = isset($_GET['version']) ? explode('|', $_GET['version']) : null;
    $getIssueResolutionIds = isset($_GET['resolution']) ? explode('|', $_GET['resolution']) : null;

    // date filters
    $getDateDueAfter = isset($_GET['date_due_after']) ? $_GET['date_due_after'] : null;
    $getDateDueBefore = isset($_GET['date_due_before']) ? $_GET['date_due_before'] : null;

    $getDateCreatedAfter = isset($_GET['date_created_after']) ? $_GET['date_created_after'] : null;
    $getDateCreatedBefore = isset($_GET['date_created_before']) ? $_GET['date_created_before'] : null;

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
                                 'sort_order' => $getSortOrder,
                                 'render_checkbox' => true,
                                 'checkbox_in_header' => true,
                                 'date_due_after' => $getDateDueAfter,
                                 'date_due_before' => $getDateDueBefore,
                                 'date_created_after' => $getDateCreatedAfter,
                                 'date_created_before' => $getDateCreatedBefore);

    $parseURLData = parse_url($_SERVER['REQUEST_URI']);

    if (isset($parseURLData['query'])) {
        UbirimiContainer::get()['session']->set('bulk_change_choose_issue_query_url', $parseURLData['query']);
        if (Util::searchQueryNotEmpty($getSearchParameters)) {

            $issues = Issue::getByParameters($getSearchParameters, $loggedInUserId, null, $loggedInUserId);

            $issuesCount = $issues->num_rows;
            $getSearchParameters['link_to_page'] = '/yongo/issue/printable-list';
        }
    }

    $columns = array('code', 'summary', 'priority', 'status', 'created', 'updated', 'reporter', 'assignee');
    $menuSelectedCategory = 'issue';

    $errorNoIssuesSelected = false;

    if (isset($_POST['next_step_2'])) {
        $issueIdsArray = array();
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 15) == "issue_checkbox_") {
                $issueIdsArray[] = (int)str_replace("issue_checkbox_", "", $key);
            }
        }
        if (count($issueIdsArray)) {
            UbirimiContainer::get()['session']->set('bulk_change_issue_ids', $issueIdsArray);
            header('Location: /yongo/issue/bulk-operation');
        } else {
            $errorNoIssuesSelected = true;
        }
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Bulk: Choose Issues';

    require_once __DIR__ . '/../../../Resources/views/issue/bulk/ChooseIssue.php';