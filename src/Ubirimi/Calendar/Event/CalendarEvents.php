<?php

namespace Ubirimi\Calendar\Event;

/**
 * Events triggered by Calendar product
 */
class CalendarEvents
{
    /**
     * Calendar Share Event
     *
     * Event triggered when a Calendar is shared.
     * The event will pass a Ubirimi\Calendar\CalendarEvent Event.
     */
    const CALENDAR_SHARE = 'calendar.share_event';

    /**
     * Calendar Event Guest Event
     *
     * Event triggered when someone is added as Gust to a Calendar Event.
     * The Event will pass a Ubirimi\Calendar\CalendarEvent Event
     */
    const CALENDAR_EVENT_GUEST = 'calendar.event_guest';
}