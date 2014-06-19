<?php
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $calendarId = $_GET['id'];
    $calendar = Calendar::getById($calendarId);

    if ($calendar['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $calendarExists = false;

    if (isset($_POST['edit_calendar'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $color = Util::cleanRegularInputField($_POST['color']);

        if (empty($name))
            $emptyName = true;

        // check for duplication

        $calendarDuplicate = Calendar::getByName($loggedInUserId, mb_strtolower($name), $calendarId);
        if ($calendarDuplicate) {
            $calendarExists = true;
        }
        if (!$calendarExists && !$emptyName) {
            $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            Calendar::updateById($calendarId, $name, $description, $color, $date);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_CALENDAR, $loggedInUserId, 'UPDATE EVENTS calendar ' . $name, $date);

            header('Location: /calendar/calendars');
        }
    }

    $menuSelectedCategory = 'calendar';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / Calendar: ' . $calendar['name'] . ' / Update';

    require_once __DIR__ . '/../Resources/views/Edit.php';