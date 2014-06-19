<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueTypeScreenSchemeId = $_GET['id'];
    $issueTypeScreenScheme = IssueTypeScreenScheme::getMetaDataById($issueTypeScreenSchemeId);

    if ($issueTypeScreenScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $issueTypeScreenSchemeData = IssueTypeScreenScheme::getDataByIssueTypeScreenSchemeId($issueTypeScreenSchemeId);
    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Type Screen Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/screen/issue_type_scheme/Configure.php';