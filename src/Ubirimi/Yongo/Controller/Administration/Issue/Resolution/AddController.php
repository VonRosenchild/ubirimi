<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;
    $resolutionExists = false;

    if (isset($_POST['new_resolution'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        // check for duplication
        $resolution = IssueSettings::getByName($clientId, 'resolution', mb_strtolower($name));
        if ($resolution)
            $resolutionExists = true;

        if (!$resolutionExists && !$emptyName) {
            $currentDate = Util::getServerCurrentDateTime();
            IssueSettings::create('issue_resolution', $clientId, $name, $description, null, null, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Issue Resolution ' . $name, $currentDate);

            header('Location: /yongo/administration/issue/resolutions');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Resolution';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/resolution/Add.php';