<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Event\CalendarEvent as CalEvent;
use Ubirimi\Calendar\Event\CalendarEvents;
use Ubirimi\Calendar\Repository\Event\CalendarEvent;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Repository\User\UbirimiUser;
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
        $this->getRepository(CalendarEvent::class)->shareWithUsers($eventId, $userIds, $currentDate);

        $event = $this->getRepository(CalendarEvent::class)->getById($eventId, 'array');
        $userThatShares = $this->getRepository(UbirimiUser::class)->getById($session->get('user/id'));

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