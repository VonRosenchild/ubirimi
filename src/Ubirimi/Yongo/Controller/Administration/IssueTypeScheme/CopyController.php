<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueTypeSchemeId = $_GET['id'];
    $type = $_GET['type'];

    $issueTypeScheme = IssueTypeScheme::getMetaDataById($issueTypeSchemeId);

    if ($issueTypeScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $duplicateName = false;

    if (isset($_POST['copy_issue_type_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name)) {
            $emptyName = true;
        }

        $duplicateIssueTypeScheme = IssueTypeScheme::getMetaDataByNameAndClientId($clientId, mb_strtolower($name));
        if ($duplicateIssueTypeScheme)
            $duplicateName = true;

        if (!$emptyName && !$duplicateName) {
            $copiedIssueTypeScheme = new IssueTypeScheme($clientId, $name, $description, $type);

            $currentDate = Util::getServerCurrentDateTime();
            $copiedIssueTypeSchemeId = $copiedIssueTypeScheme->save($currentDate);

            $issueTypeSchemeData = IssueTypeScheme::getDataById($issueTypeSchemeId);

            while ($issueTypeSchemeData && $data = $issueTypeSchemeData->fetch_array(MYSQLI_ASSOC)) {
                $copiedIssueTypeScheme->addData($copiedIssueTypeSchemeId, $data['issue_type_id'], $currentDate);
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'Copy Yongo Issue Type Scheme ' . $issueTypeScheme['name'], $currentDate);

            if ('workflow' == $type) {
                header('Location: /yongo/administration/workflows/issue-type-schemes');
            } else {
                header('Location: /yongo/administration/issue-type-schemes');
            }
        }
    }
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Issue Type Scheme';

    require_once __DIR__ . '/../../../Resources/views/administration/issue/issue_type_scheme/Copy.php';