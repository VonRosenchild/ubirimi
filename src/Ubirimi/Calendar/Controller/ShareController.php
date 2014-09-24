<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Event\CalendarEvent;
use Ubirimi\Calendar\Event\CalendarEvents;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Repository\User\User;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ShareController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $calendarId = $request->request->get('id');
        $noteContent = $request->request->get('note');
        $userIds = $request->request->get('user_id');

        $currentDate = Util::getServerCurrentDateTime();
        Calendar::deleteSharesByCalendarId($calendarId);
        $calendar = Calendar::getById($calendarId);
        $userThatShares = User::getById($session->get('user/id'));

        if ($userIds) {
            Calendar::shareWithUsers($calendarId, $userIds, $currentDate);
            $calendarEvent = new CalendarEvent(
                $calendar,
                array(
                    'userThatShares' => $userThatShares,
                    'usersToShareWith' => $userIds,
                    'noteContent' => $noteContent
                )
            );

            UbirimiContainer::get()['dispatcher']->dispatch(CalendarEvents::CALENDAR_SHARE, $calendarEvent);

            $logEvent = new LogEvent(SystemProduct::SYS_PRODUCT_CALENDAR, 'Share Calendar ' . $calendar['name']);
            UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $logEvent);
        }

        return new Response('');
    }
}