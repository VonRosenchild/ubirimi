<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $newOrder = $_POST['order'];

    AgileBoard::updateColumnOrder($newOrder);