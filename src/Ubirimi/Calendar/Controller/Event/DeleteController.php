<?php

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $eventId = $request->request->get('id');
        $recurringType = $request->request->get('recurring');

        // check if it is a shared calendar
        $isSharedEvent = CalendarEvent::getShareByUserIdAndEventId($session->get('user/id'), $eventId);

        if ($isSharedEvent) {
            // delete only the share
            CalendarEvent::deleteEventSharesByUserId($eventId, c);
        } else {
            // delete the event and the possible shares
            CalendarEvent::deleteById($eventId, $recurringType);
        }

        return new Response('');
    }
}
