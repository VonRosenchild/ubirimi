<?php
    use Ubirimi\Repository\HelpDesk\Queue;
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');
    $issuesPerPage = $session->get('user/issues_per_page');

    $projectId = $_GET['id'];
    $queueId = $_GET['queue_id'];
    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;

    $project = Project::getById($projectId);
    $queueSelected = Queue::getById($queueId);

    $columns = explode('#', $queueSelected['columns']);

    $SLAs = SLA::getByProjectId($projectId);
    if ($SLAs) {
        $slaSelected = $SLAs->fetch_array(MYSQLI_ASSOC);
        $SLAs->data_seek(0);
    }

    $queues = Queue::getByProjectId($projectId);
    if ($queues) {
        $whereSQL = Issue::prepareWhereClauseFromQueue($queueSelected['definition'], $loggedInUserId, $projectId, $clientId);
        $whereSQL = 'issue_main_table.project_id = ' . $projectId . ' AND ' . $whereSQL;

        $getSearchParameters = array();
        $getSearchParameters['page'] = $page;
        $getSearchParameters['issues_per_page'] = 50;

        $issuesResult = Issue::getByParameters($getSearchParameters, $loggedInUserId, $whereSQL, $loggedInUserId);

        $issues = $issuesResult[0];

        $issuesCount = $issuesResult[1];
        $countPages = ceil($issuesCount / $issuesPerPage);
        $getSearchParameters = array();
        $getSearchParameters['page'] = $page;
        $getSearchParameters['count_pages'] = $countPages;
        $getSearchParameters['link_to_page'] = '/helpdesk/queues/' . $projectId . '/' . $queueId;
    }
    $menuSelectedCategory = 'help_desk';
    $menuProjectCategory = 'queue';
    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Help Desks';

    require_once __DIR__ . '/../../Resources/views/queue/View.php';