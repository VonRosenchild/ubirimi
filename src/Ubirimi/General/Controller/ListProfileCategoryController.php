<?php

use Ubirimi\General\Repository\UserProfileCategory\UserProfileCategory;
use Ubirimi\Util;

Util::checkUserIsLoggedInAndRedirect();

$session->set('selected_product_id', -1);

$menuSelectedCategory = 'general_user';
$sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / User Profile Manager';

$profileCategories = UserProfileCategory::getByClientId($clientId);
require_once __DIR__ . '/../Resources/views/ListProfileCategory.php';