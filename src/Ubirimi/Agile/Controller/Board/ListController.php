<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'agile';
    $boards = AgileBoard::getByClientId($clientId);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Boards';

    require_once __DIR__ . '/../../Resources/views/board/List.php';