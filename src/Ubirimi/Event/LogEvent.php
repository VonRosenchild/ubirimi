<?php

namespace Ubirimi\Event;

use Symfony\Component\EventDispatcher\Event;

class LogEvent extends Event
{
    private $productId;
    private $message;

    public function __construct($productId, $message)
    {
        $this->productId = $productId;
        $this->message = $message;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getMessage()
    {
        return $this->message;
    }
}