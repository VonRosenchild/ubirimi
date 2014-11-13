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

namespace Ubirimi\Calendar\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ubirimi\Calendar\Event\CalendarEvent as Event;
use Ubirimi\Calendar\Event\CalendarEvents;
use Ubirimi\Container\UbirimiContainer;

class CalendarEventSubscriber implements EventSubscriberInterface
{
    public function onCalendarShare(Event $event)
    {
        UbirimiContainer::get()['calendar.email']->shareCalendar(
            $event->getCalendar(),
            $event->getExtra()['userThatShares'],
            $event->getExtra()['usersToShareWith'],
            $event->getExtra()['noteContent']
        );
    }

    public function onCalendarEventGuest(Event $event)
    {
        UbirimiContainer::get()['calendar.email']->shareEvent(
            $event->getExtra()['event'],
            $event->getExtra()['userThatShares'],
            $event->getExtra()['usersToShareWith'],
            $event->getExtra()['noteContent']
        );
    }

    public static function getSubscribedEvents()
    {
        return array(
            CalendarEvents::CALENDAR_SHARE => 'onCalendarShare',
            CalendarEvents::CALENDAR_EVENT_GUEST => 'onCalendarEventGuest'
        );
    }
}