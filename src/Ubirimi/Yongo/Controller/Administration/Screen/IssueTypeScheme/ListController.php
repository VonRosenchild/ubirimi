<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;

    Util::checkUserIsLoggedInAndRedirect();
    $issueTypeScreenSchemes = IssueTypeScreenScheme::getByClientId($clientId);
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Type Screen Schemes';

    require_once __DIR__ . '/../../../../Resources/views/administration/screen/issue_type_scheme/List.php';