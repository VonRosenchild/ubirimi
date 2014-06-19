<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $filterGroupId = isset($_GET['group_id']) ? $_GET['group_id'] : null;

    if ($filterGroupId) {
        $group = Group::getMetadataById($filterGroupId);
        if ($group['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }
    }

    $users = Client::getUsers($clientId, $filterGroupId, null, 1);

    $menuSelectedCategory = 'user';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Users';
    $allGroups = Group::getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_YONGO);

    require_once __DIR__ . '/../../../Resources/views/administration/user/List.php';