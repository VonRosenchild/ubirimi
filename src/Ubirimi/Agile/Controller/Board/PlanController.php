<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Agile\Repository\AgileSprint;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'agile';

    $boardId = $_GET['id'];
    $searchQuery = isset($_GET['q']) ? $_GET['q'] : null;
    $onlyMyIssuesFlag = isset($_GET['only_my']) ? 1 : 0;

    $session->set('last_selected_board_id', $boardId);
    $board = AgileBoard::getById($boardId);

    if ($board['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $sprintsNotStarted = AgileSprint::getNotStarted($boardId);

    $boardProjects = AgileBoard::getProjects($boardId, 'array');
    $currentStartedSprint = AgileSprint::getStarted($boardId);
    $lastCompletedSprint = AgileSprint::getLastCompleted($boardId);

    $lastColumn = AgileBoard::getLastColumn($boardId);
    $completeStatuses = AgileBoard::getColumnStatuses($lastColumn['id'], 'array', 'id');

    $columns = array('type', 'code', 'summary', 'priority');

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Board: ' . $board['name'] . ' / Plan View';

    require_once __DIR__ . '/../../Resources/views/board/Plan.php';