<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueFilter;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'agile';

    $boardId = $_GET['id'];
    $board = AgileBoard::getById($boardId);

    if ($board['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $boardProjects = AgileBoard::getProjects($boardId, 'array');
    $filter = IssueFilter::getById($board['filter_id']);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Board / Filter';

    require_once __DIR__ . '/../../Resources/views/board/EditDataFilter.php';