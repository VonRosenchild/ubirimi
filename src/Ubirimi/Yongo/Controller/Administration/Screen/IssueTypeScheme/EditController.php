<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueTypeScreenSchemeId = $_GET['id'];
    $emptyName = false;
    $issueTypeScreenScheme = IssueTypeScreenScheme::getMetaDataById($issueTypeScreenSchemeId);

    if ($issueTypeScreenScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    if (isset($_POST['edit_issue_type_screen_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            IssueTypeScreenScheme::updateMetaDataById($issueTypeScreenSchemeId, $name, $description, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Issue Type Screen Scheme ' . $name, $currentDate);

            header('Location: /yongo/administration/screens/issue-types');
        }
    }
    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Type Screen Scheme';
    require_once __DIR__ . '/../../../../Resources/views/administration/screen/issue_type_scheme/Edit.php';