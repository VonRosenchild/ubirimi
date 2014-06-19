<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $boardId = $_POST['id'];
    $board = AgileBoard::getById($boardId);

    AgileBoard::deleteById($boardId);

    $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Log::add($clientId, SystemProduct::SYS_PRODUCT_CHEETAH, $loggedInUserId, 'DELETE Cheetah Agile Board ' . $board['name'], $date);