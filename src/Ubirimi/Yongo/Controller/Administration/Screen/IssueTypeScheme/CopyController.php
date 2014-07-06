<?php
    use Ubirimi\Repository\Log;
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

    $emptyName = false;
    $duplicateName = false;

    if (isset($_POST['copy_issue_type_screen_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name)) {
            $emptyName = true;
        }

        $duplicateIssueTypeScreenScheme = IssueTypeScreenScheme::getMetaDataByNameAndClientId($clientId, mb_strtolower($name));
        if ($duplicateIssueTypeScreenScheme)
            $duplicateName = true;

        if (!$emptyName && !$duplicateName) {
            $copiedIssueTypeScreenScheme = new IssueTypeScreenScheme($clientId, $name, $description);

            $currentDate = Util::getServerCurrentDateTime();
            $copiedIssueTypeScreenSchemeId = $copiedIssueTypeScreenScheme->save($currentDate);

            $issueTypeScreenSchemeData = IssueTypeScreenScheme::getDataByIssueTypeScreenSchemeId($issueTypeScreenSchemeId);

            while ($issueTypeScreenSchemeData && $data = $issueTypeScreenSchemeData->fetch_array(MYSQLI_ASSOC)) {
                $copiedIssueTypeScreenScheme->addDataComplete($copiedIssueTypeScreenSchemeId, $data['issue_type_id'], $data['screen_scheme_id'], $currentDate);
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'Copy Yongo Issue Type Scheme ' . $issueTypeScheme['name'], $currentDate);

            header('Location: /yongo/administration/screens/issue-types');
        }
    }
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Issue Type Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/screen/issue_type_scheme/Copy.php';