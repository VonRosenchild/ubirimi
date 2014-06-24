<?php
    use Ubirimi\Calendar\Repository\CalendarEvent;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $eventId = $_POST['id'];
    $offset = $_POST['offset'];

    CalendarEvent::updateEventOffset($eventId, $offset);