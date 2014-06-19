<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $boardId = $_POST['board_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    AgileBoard::addColumn($boardId, $name, $description);