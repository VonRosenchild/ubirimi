<?php
    use Ubirimi\Calendar\Event\CalendarEvent;
    use Ubirimi\Calendar\Event\CalendarEvents;
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Event\LogEvent;
    use Ubirimi\Event\UbirimiEvents;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $calendarId = $_POST['id'];
    $noteContent = $_POST['note'];
    $userIds = $_POST['user_id'];

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Calendar::shareWithUsers($calendarId, $userIds, $currentDate);

    $userThatShares = User::getById($loggedInUserId);
    $calendar = Calendar::getById($calendarId);

    $calendarEvent = new CalendarEvent($calendar, array('userThatShares' => $userThatShares, 'usersToShareWith' => $userIds, 'noteContent' => $noteContent));
    $logEvent = new LogEvent(SystemProduct::SYS_PRODUCT_CALENDAR, 'Share Calendar ' . $calendar['name']);

    UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $logEvent);
    UbirimiContainer::get()['dispatcher']->dispatch(CalendarEvents::CALENDAR_SHARE, $calendarEvent);
