<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;

    Util::checkUserIsLoggedInAndRedirect();
    $issueTypeSchemes = IssueTypeScheme::getByClientId($clientId, 'workflow');
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow Issue Type Schemes';

    require_once __DIR__ . '/../../../../Resources/views/administration/workflow/issue_type_scheme/List.php';