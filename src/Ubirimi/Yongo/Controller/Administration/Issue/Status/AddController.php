<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;
    $status_exists = false;

    if (isset($_POST['new_status'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        $status = IssueSettings::getByName($clientId, 'status', mb_strtolower($name));

        if ($status)
            $status_exists = true;

        if (!$emptyName && !$status_exists) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            IssueSettings::create('issue_status', $clientId, $name, $description, null, null, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Issue Status ' . $name, $currentDate);

            header('Location: /yongo/administration/issue/statuses');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Status';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/status/Add.php';