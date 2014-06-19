<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueSecuritySchemes = IssueSecurityScheme::getByClientId($clientId);

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Security Schemes';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/List.php';