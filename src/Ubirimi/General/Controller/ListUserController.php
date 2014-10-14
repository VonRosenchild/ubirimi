<?php

    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', -1);
    $filterGroupId = isset($_GET['group_id']) ? $_GET['group_id'] : null;

    $users = $this->getRepository('ubirimi.general.client')->getUsers($clientId, $filterGroupId, null, 1);
    $menuSelectedCategory = 'general_user';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / Users';

    require_once __DIR__ . '/../Resources/views/ListUser.php';