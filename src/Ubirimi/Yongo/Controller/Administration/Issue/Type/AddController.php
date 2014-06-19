<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;

    Util::checkUserIsLoggedInAndRedirect();

    $subTaskFlag = (isset($_GET['type'])) ? 1 : 0;

    $emptyName = false;

    if (isset($_POST['new_type'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $iconName = 'generic.png';
            $newIssueTypeId = IssueSettings::createIssueType($clientId, $name, $description, $subTaskFlag, $iconName, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Issue Type ' . $name, $currentDate);

            if ($subTaskFlag)
                header('Location: /yongo/administration/issue-sub-tasks');
            else
                header('Location: /yongo/administration/issue-types');
        }
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Type';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/type/Add.php';