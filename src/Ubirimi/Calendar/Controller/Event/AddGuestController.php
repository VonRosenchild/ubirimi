<?php

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Event\CalendarEvent as CalEvent;
use Ubirimi\Calendar\Event\CalendarEvents;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddGuestController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $eventId = $request->request->get('id');
        $noteContent = $request->request->get('note');
        $userIds = $request->request->get('user_id');

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('calendar.event.event')->shareWithUsers($eventId, $userIds, $currentDate);

        $event = $this->getRepository('calendar.event.event')->getById($eventId, 'array');
        $userThatShares = $this->getRepository('ubirimi.user.user')->getById($session->get('user/id'));

        $logEvent = new LogEvent(SystemProduct::SYS_PRODUCT_CALENDAR, 'Add Guest for Event ' . $event['name']);
        $calendarEvent = new CalEvent(
            null,
            array(
                'event' => $event,
                'userThatShares' => $userThatShares,
                'usersToShareWith' => $userIds,
                'noteContent' => $noteContent
            )
        );

        UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $logEvent);
        UbirimiContainer::get()['dispatcher']->dispatch(CalendarEvents::CALENDAR_EVENT_GUEST, $calendarEvent);

        return new Response('');
    }
}