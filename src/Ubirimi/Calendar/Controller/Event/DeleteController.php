<?php
    use Ubirimi\Calendar\Repository\CalendarEvent;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $eventId = $_POST['id'];
    $recurringType = isset($_POST['recurring']) ? $_POST['recurring'] : null;

    // check if it is a shared calendar
    $isSharedEvent = CalendarEvent::getShareByUserIdAndEventId($loggedInUserId, $eventId);

    if ($isSharedEvent) {
        // delete only the share
        CalendarEvent::deleteEventSharesByUserId($eventId, $loggedInUserId);
    } else {
        // delete the event and the possible shares
        CalendarEvent::deleteById($eventId, $recurringType);
    }
