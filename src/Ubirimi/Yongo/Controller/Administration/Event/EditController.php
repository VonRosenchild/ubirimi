<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueEvent;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'system';
    $emptyName = false;

    $eventId = $_GET['id'];
    $event = IssueEvent::getById($eventId);

    if ($event['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    if (isset($_POST['edit_event'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $currentDate = Util::getServerCurrentDateTime();
            IssueEvent::updateById($eventId, $name, $description, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Event ' . $name, $currentDate);
            header('Location: /yongo/administration/events');
        }
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Event';
    require_once __DIR__ . '/../../../Resources/views/administration/event/edit.php';