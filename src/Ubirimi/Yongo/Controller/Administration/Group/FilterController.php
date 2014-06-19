<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $filters = array();
    if (isset($_POST['name_filter'])) {
        $filters['name'] = $_POST['name_filter'];
    }

    $groups = Client::getGroupsByClientIdAndProductIdAndFilters($clientId, SystemProduct::SYS_PRODUCT_YONGO, $filters);

    $menuSelectedCategory = 'group';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Groups';

    require_once __DIR__ . '/../../../Resources/views/administration/group/_list_group.php';