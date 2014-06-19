<?php
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'home';
    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_HELP_DESK);

    $userId = $_GET['id'];
    $user = User::getById($userId);

    require_once __DIR__ . '/../../Resources/views/customer_portal/ViewUserProfile.php';