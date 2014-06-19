<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_GET['id'];
    $issueStatus = IssueSettings::getById($Id, 'status');

    if ($issueStatus['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $statusExists = false;

    if (isset($_POST['edit_status'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        // check for duplication
        $status = IssueSettings::getByName($clientId, 'status', mb_strtolower($name), $Id);
        if ($status)
            $statusExists = true;

        if (!$statusExists && !$emptyName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            IssueSettings::updateById($Id, 'status', $name, $description, null, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Issue Status ' . $name, $currentDate);

            header('Location: /yongo/administration/issue/statuses');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Status';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/status/Edit.php';