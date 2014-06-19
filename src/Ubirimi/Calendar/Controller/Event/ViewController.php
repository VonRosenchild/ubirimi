<?php
    use Ubirimi\Calendar\Repository\CalendarEvent;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);

    $eventId = $_GET['id'];

    $event = CalendarEvent::getById($eventId, 'array');
    if ($event['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    // check if the event is a shared event
    $sharedEvent = CalendarEvent::getShareByUserIdAndEventId($loggedInUserId, $event['id']);

    $menuSelectedCategory = 'calendars';

    $month = date('n');
    $year = date('Y');

    $eventReminders = CalendarEvent::getReminders($eventId);
    $sourcePageLink = $_GET['source'];

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / ' . $event['name'];

    require_once __DIR__ . '/../../Resources/views/event/View.php';