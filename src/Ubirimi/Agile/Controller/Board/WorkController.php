<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Agile\Repository\AgileSprint;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'agile';

    $sprintId = $_GET['id'];
    $boardId = $_GET['board_id'];
    $onlyMyIssuesFlag = isset($_GET['only_my']) ? 1 : 0;
    $board = AgileBoard::getById($boardId);

    if ($board['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $swimlaneStrategy = $board['swimlane_strategy'];

    if ($sprintId == -1) {
        $sprint = null;
    } else {
        $sprint = AgileSprint::getById($sprintId);
        $sprintBoardId = $sprint['agile_board_id'];
        if ($sprintBoardId != $boardId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }
    }

    $columns = AgileBoard::getColumns($boardId, 'array');
    $lastCompletedSprint = AgileSprint::getLastCompleted($boardId);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Board: ' . $board['name'] . ' / Work View';

    require_once __DIR__ . '/../../Resources/views/board/Work.php';