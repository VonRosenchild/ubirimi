<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;
    $issueSecuritySchemeId = $_GET['id'];
    $issueSecurityScheme = IssueSecurityScheme::getMetaDataById($issueSecuritySchemeId);
    if (isset($_POST['add_issue_security_scheme_level'])) {

        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            IssueSecurityScheme::addLevel($issueSecuritySchemeId, $name, $description, $currentDate);
            header('Location: /yongo/administration/issue-security-scheme-levels/' . $issueSecuritySchemeId);
        }
    }

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Security Scheme Level';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/AddLevel.php';