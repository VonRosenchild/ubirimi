<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueSecuritySchemeId = $_GET['id'];
    $issueSecurityScheme = IssueSecurityScheme::getMetaDataById($issueSecuritySchemeId);

    if ($issueSecurityScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $menuSelectedCategory = 'issue';

    $issueSecuritySchemeLevels = IssueSecurityScheme::getLevelsByIssueSecuritySchemeId($issueSecuritySchemeId);
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Security Scheme Levels';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/ListLevel.php';