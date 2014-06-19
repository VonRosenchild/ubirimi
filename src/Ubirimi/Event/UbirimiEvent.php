<?php

namespace Ubirimi\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * General purpose event class
 */
class UbirimiEvent extends Event
{
    private $data;

    public function __construct($data = array())
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}