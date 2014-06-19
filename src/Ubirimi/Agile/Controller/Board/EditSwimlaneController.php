<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'agile';

    $boardId = $_GET['id'];
    $board = AgileBoard::getById($boardId);

    if ($board['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Board / Swimlanes';

    require_once __DIR__ . '/../../Resources/views/board/EditDataSwimlane.php';