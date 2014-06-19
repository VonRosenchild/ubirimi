<?php
    use Ubirimi\Calendar\Event\CalendarEvent as CalEvent;
    use Ubirimi\Calendar\Event\CalendarEvents;
    use Ubirimi\Calendar\Repository\CalendarEvent;
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Event\LogEvent;
    use Ubirimi\Event\UbirimiEvents;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $eventId = $_POST['id'];
    $noteContent = $_POST['note'];
    $userIds = $_POST['user_id'];

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    CalendarEvent::shareWithUsers($eventId, $userIds, $currentDate);

    $event = CalendarEvent::getById($eventId, 'array');
    $userThatShares = User::getById($loggedInUserId);

    $logEvent = new LogEvent(SystemProduct::SYS_PRODUCT_CALENDAR, 'Add Guest for Event ' . $event['name']);
    $calendarEvent = new CalEvent(null, array('event' => $event, 'userThatShares' => $userThatShares, 'usersToShareWith' => $userIds, 'noteContent' => $noteContent));

    UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $logEvent);
    UbirimiContainer::get()['dispatcher']->dispatch(CalendarEvents::CALENDAR_EVENT_GUEST, $calendarEvent);

    echo 'The selected guests have been invited';