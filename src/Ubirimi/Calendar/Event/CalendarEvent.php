<?php

namespace Ubirimi\Calendar\Event;

use Symfony\Component\EventDispatcher\Event;

class CalendarEvent extends Event
{
    private $calendar;
    private $extra;

    public function __construct($calendar = null, $extra = array())
    {
        $this->calendar = $calendar;
        $this->extra = $extra;
    }

    public function getCalendar()
    {
        return $this->calendar;
    }

    public function getExtra()
    {
        return $this->extra;
    }
}