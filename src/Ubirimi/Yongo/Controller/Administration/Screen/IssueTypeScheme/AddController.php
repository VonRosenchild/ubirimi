<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueType;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;

    $allIssueTypes = IssueType::getAll($clientId);

    if (isset($_POST['new_issue_type_screen_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $issueTypeScreenScheme = new IssueTypeScreenScheme($clientId, $name, $description);
            $currentDate = Util::getServerCurrentDateTime();
            $issueTypeScreenSchemeId = $issueTypeScreenScheme->save($currentDate);

            $issueTypes = IssueType::getAll($clientId);
            while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)) {
                IssueTypeScreenScheme::addData($issueTypeScreenSchemeId, $issueType['id'], $currentDate);
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Issue Type Screen Scheme ' . $name, $currentDate);

            header('Location: /yongo/administration/screens/issue-types');
        }
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Type Screen Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/screen/issue_type_scheme/Add.php';