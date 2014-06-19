<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Agile\Repository\AgileSprint;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'agile';

    $sprintId = $_GET['id'];
    $boardId = $_GET['board_id'];
    $chart = $_GET['chart'];

    $board = AgileBoard::getById($boardId);

    if ($board['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $currentStartedSprint = AgileSprint::getStarted($boardId);

    if ($sprintId != -1) {
        $selectedSprint = AgileSprint::getById($sprintId);
        $completedSprints = AgileSprint::getCompleted($boardId, $sprintId);
        $doneIssues = AgileSprint::getCompletedUncompletedIssuesBySprintId($sprintId, 1);
        $notDoneIssues = AgileSprint::getCompletedUncompletedIssuesBySprintId($sprintId, 0);
    }

    $availableCharts = array('sprint_report' => 'Sprint Report', 'velocity_chart' => 'Velocity Chart');

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Board: ' . $board['name'] . ' / Report View';

    require_once __DIR__ . '/../../Resources/views/board/Report.php';