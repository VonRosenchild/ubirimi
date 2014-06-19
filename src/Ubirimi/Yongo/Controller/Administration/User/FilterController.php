<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $filters = array();
    if (isset($_POST['username_filter'])) {
        $filters['username'] = $_POST['username_filter'];
    }
    if (isset($_POST['fullname_filter'])) {
        $filters['fullname'] = $_POST['fullname_filter'];
    }
    if (isset($_POST['group_filter'])) {
        $filters['group'] = $_POST['group_filter'];
    }
    $users = Client::getUsersByClientIdAndProductIdAndFilters($clientId, SystemProduct::SYS_PRODUCT_YONGO, $filters);

    $menuSelectedCategory = 'user';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Users';

    require_once __DIR__ . '/../../../Resources/views/administration/user/_list_user.php';