<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_GET['id'];
    $issuePriority = IssueSettings::getById($Id, 'priority');

    if ($issuePriority['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $priorityExists = false;

    if (isset($_POST['edit_priority'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $color = $_POST['color'];

        if (empty($name))
            $emptyName = true;

        // check for duplication
        $priority = IssueSettings::getByName($clientId, 'priority', mb_strtolower($name), $Id);
        if ($priority)
            $priorityExists = true;

        if (!$priorityExists && !$emptyName) {
            $currentDate = Util::getServerCurrentDateTime();
            IssueSettings::updateById($Id, 'priority', $name, $description, $color, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Issue Priority ' . $name, $currentDate);

            header('Location: /yongo/administration/issue/priorities');
        }
    }

    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Priority';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/priority/Edit.php';