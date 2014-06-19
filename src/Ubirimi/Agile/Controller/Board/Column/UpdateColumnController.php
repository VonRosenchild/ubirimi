<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $boardId = $_POST['board_id'];
    $StatusId = $_POST['status_id'];
    $newColumnId = $_POST['new_column_id'];

    AgileBoard::deleteStatusFromColumn($boardId, $StatusId);
    if ($newColumnId)
        AgileBoard::addStatusToColumn($newColumnId, $StatusId);