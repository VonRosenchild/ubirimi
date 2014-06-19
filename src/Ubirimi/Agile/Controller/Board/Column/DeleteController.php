<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $columnId = $_POST['id'];

    AgileBoard::deleteColumn($columnId);