<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;

    Util::checkUserIsLoggedInAndRedirect();
    $type = 'project';
    $issueTypeSchemes = IssueTypeScheme::getByClientId($clientId, $type);
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Type Schemes';

    require_once __DIR__ . '/../../../Resources/views/administration/issue/issue_type_scheme/List.php';