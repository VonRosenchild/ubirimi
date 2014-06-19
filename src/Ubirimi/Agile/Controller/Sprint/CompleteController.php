<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Agile\Repository\AgileSprint;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $sprintId = $_POST['id'];
    $boardId = $_POST['board_id'];

    $sprint = AgileSprint::getById($sprintId);
    $lastColumn = AgileBoard::getLastColumn($boardId);
    $completeStatuses = AgileBoard::getColumnStatuses($lastColumn['id'], 'array', 'id');

    AgileBoard::transferNotDoneIssues($boardId, $sprintId, $completeStatuses);

    AgileSprint::complete($sprintId);