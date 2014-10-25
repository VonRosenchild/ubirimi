<?php

namespace Ubirimi\Service;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiLog;

class LogService extends UbirimiService
{
    public function log($productId, $message)
    {
        UbirimiContainer::get()['repository']->get(UbirimiLog::class)->add(
            $this->session->get('client/id'),
            $productId,
            $this->session->get('user/id'),
            $message
        );
    }
}