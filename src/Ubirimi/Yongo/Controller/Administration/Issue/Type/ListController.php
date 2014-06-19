<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueType;

    Util::checkUserIsLoggedInAndRedirect();
    $types = IssueType::getAll($clientId);

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Types';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/type/List.php';