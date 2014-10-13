<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $filterGroupId = isset($_GET['group_id']) ? $_GET['group_id'] : null;

    $users = $this->getRepository('ubirimi.general.client')->getUsers($clientId, $filterGroupId);

    $menuSelectedCategory = 'doc_users';

    require_once __DIR__ . '/../../Resources/views/administration/ListUsers.php';