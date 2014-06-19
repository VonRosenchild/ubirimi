<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueSecuritySchemeLevelId = $_GET['id'];
    $issueSecuritySchemeLevel = IssueSecurityScheme::getLevelById($issueSecuritySchemeLevelId);
    $issueSecurityScheme = IssueSecurityScheme::getMetaDataById($issueSecuritySchemeLevel['issue_security_scheme_id']);

    if ($issueSecurityScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    if (isset($_POST['edit_issue_security_scheme_level'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            IssueSecurityScheme::updateLevelById($issueSecuritySchemeLevelId, $name, $description, $date);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Issue Security Scheme Level ' . $name, $date);
            header('Location: /yongo/administration/issue-security-scheme-levels/' . $issueSecurityScheme['id']);
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Security Scheme Level';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/EditLevel.php';