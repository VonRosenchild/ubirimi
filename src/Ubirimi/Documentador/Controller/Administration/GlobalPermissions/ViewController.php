<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'doc_users';
    $documentatorSettings = $this->getRepository('ubirimi.general.client')->getDocumentatorSettings($clientId);
    $session->set('documentator/settings', $documentatorSettings);

    $users = $this->getRepository('ubirimi.user.user')->getByClientId($clientId);
    $groups = $this->getRepository('ubirimi.user.group')->getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
    $globalsPermissions = GlobalPermission::getAllByProductId(SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

    require_once __DIR__ . '/../../../Resources/views/administration/globalpermissions/View.php';