<?php

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