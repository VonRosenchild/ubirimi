<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;
    if (isset($_POST['add_issue_security_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $issueSecurityScheme = new IssueSecurityScheme($clientId, $name, $description);
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $issueSecurityScheme->save($currentDate);

            header('Location: /yongo/administration/issue-security-schemes');
        }
    }

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Security Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/Add.php';