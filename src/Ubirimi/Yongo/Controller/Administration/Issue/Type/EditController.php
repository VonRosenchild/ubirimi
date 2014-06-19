<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;
    use Ubirimi\Yongo\Repository\Issue\IssueType;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_GET['id'];
    $issueType = IssueType::getById($Id);

    if ($issueType['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $typeExists = false;

    if (isset($_POST['edit_type'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        // check for duplication
        $type = IssueSettings::getByName($clientId, 'type', mb_strtolower($name), $Id);
        if ($type)
            $typeExists = true;

        if (!$typeExists && !$emptyName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            IssueType::updateById($Id, $name, $description, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Issue Type ' . $name, $currentDate);

            header('Location: /yongo/administration/issue-types');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Type';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/type/Edit.php';