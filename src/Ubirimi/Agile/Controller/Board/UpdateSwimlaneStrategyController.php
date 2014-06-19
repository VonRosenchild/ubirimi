<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $boardId = $_POST['id'];
    $strategy = $_POST['strategy'];

    AgileBoard::updateSwimlaneStrategy($boardId, $strategy);