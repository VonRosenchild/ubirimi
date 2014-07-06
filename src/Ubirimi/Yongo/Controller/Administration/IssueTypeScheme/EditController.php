<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueType;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueTypeSchemeId = $_GET['id'];

    $emptyName = false;
    $typeExists = false;

    $issueTypeScheme = IssueTypeScheme::getMetaDataById($issueTypeSchemeId);

    if ($issueTypeScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $allIssueTypes = IssueType::getAll($clientId);
    $schemeIssueTypes = IssueTypeScheme::getDataById($issueTypeSchemeId);

    $type = $issueTypeScheme['type'];
    $name = $issueTypeScheme['name'];
    $description = $issueTypeScheme['description'];

    if (isset($_POST['edit_type_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $currentDate = Util::getServerCurrentDateTime();

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            IssueTypeScheme::updateMetaDataById($issueTypeSchemeId, $name, $description);
            IssueTypeScheme::deleteDataByIssueTypeSchemeId($issueTypeSchemeId);
            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 11) == 'issue_type_') {
                    $issueTypeId = str_replace('issue_type_', '', $key);
                    IssueTypeScheme::addData($issueTypeSchemeId, $issueTypeId, $currentDate);
                }
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Issue Type Scheme ' . $name, $currentDate);

            if ($type == 'project')
                header('Location: /yongo/administration/issue-type-schemes');
            else
                header('Location: /yongo/administration/workflows/issue-type-schemes');
        }
    }
    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Type Scheme';
    require_once __DIR__ . '/../../../Resources/views/administration/issue/issue_type_scheme/Edit.php';