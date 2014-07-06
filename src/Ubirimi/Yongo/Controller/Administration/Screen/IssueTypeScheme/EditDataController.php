<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
    use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueTypeScreenSchemeDataId = $_GET['id'];
    $screenSchemes = ScreenScheme::getMetaDataByClientId($clientId);
    $issueTypeScreenSchemeData = IssueTypeScreenScheme::getDataById($issueTypeScreenSchemeDataId);

    $screenSchemeId = $issueTypeScreenSchemeData['issue_type_screen_scheme_id'];
    $issueTypeScreenSchemeMetaData = IssueTypeScreenScheme::getMetaDataById($screenSchemeId);

    if ($issueTypeScreenSchemeMetaData['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    if (isset($_POST['edit_issue_type_screen_scheme_data'])) {
        $currentDate = Util::getServerCurrentDateTime();

        $screenSchemeId = Util::cleanRegularInputField($_POST['screen_scheme']);
        $issueTypeId = Util::cleanRegularInputField($_POST['issue_type']);

        IssueTypeScreenScheme::updateDataById($screenSchemeId, $issueTypeId, $issueTypeScreenSchemeMetaData['id']);

        Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Issue Type Screen Scheme Data ' . $issueTypeScreenSchemeMetaData['name'], $currentDate);

        header('Location: /yongo/administration/screen/configure-scheme-issue-type/' . $issueTypeScreenSchemeMetaData['id']);
    }
    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Type Screen Scheme Data';
    require_once __DIR__ . '/../../../../Resources/views/administration/screen/issue_type_scheme/EditData.php';