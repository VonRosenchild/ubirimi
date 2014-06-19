<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueLinkType;

    Util::checkUserIsLoggedInAndRedirect();
    $menuSelectedCategory = 'system';

    $linkTypes = IssueLinkType::getByClientId($clientId);
    $issueLinkingFlag = $session->get('yongo/settings/issue_linking_flag');

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Link Types';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/link/List.php';;