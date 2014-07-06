<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueEvent;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

    $menuSelectedCategory = 'system';
    $emptyName = false;

    if (isset($_POST['new_event'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $currentDate = Util::getServerCurrentDateTime();

            $event = new IssueEvent($clientId, $name, $description);
            $event->save($currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Event ' . $name, $currentDate);

            header('Location: /yongo/administration/events');
        }
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Event';

    require_once __DIR__ . '/../../../Resources/views/administration/event/add.php';