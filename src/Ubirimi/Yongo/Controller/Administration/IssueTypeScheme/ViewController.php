<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueTypeSchemeId = $_GET['id'];
    $issueTypeScheme = IssueTypeScheme::getById($issueTypeSchemeId);

    if ($issueTypeScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Type Scheme';

    require_once __DIR__ . '/../../../Resources/views/administration/issue/issue_type_scheme/View.php';