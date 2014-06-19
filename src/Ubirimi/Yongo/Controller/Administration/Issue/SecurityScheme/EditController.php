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

    $emptyName = false;
    if (isset($_POST['edit_issue_security_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            IssueSecurityScheme::updateMetaDataById($issueSecuritySchemeId, $name, $description);
            header('Location: /yongo/administration/issue-security-schemes');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Security Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/Edit.php';