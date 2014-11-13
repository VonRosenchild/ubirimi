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