<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_GET['id'];
    $issueResolution = IssueSettings::getById($Id, 'resolution');

    if ($issueResolution['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $resolution_exists = false;

    if (isset($_POST['edit_resolution'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        // check for duplication
        $resolution = IssueSettings::getByName($clientId, 'resolution', mb_strtolower($name), $Id);
        if ($resolution)
            $resolution_exists = true;

        if (!$resolution_exists && !$emptyName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            IssueSettings::updateById($Id, 'resolution', $name, $description, null, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Issue Resolution ' . $name, $currentDate);

            header('Location: /yongo/administration/issue/resolutions');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Resolution';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/resolution/Edit.php';