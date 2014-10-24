<?php

namespace Ubirimi\Service;

use Ubirimi\Repository\General\UbirimiLog;

class LogService extends UbirimiService
{
    public function log($productId, $message)
    {
        UbirimiLog::add(
            $this->session->get('client/id'),
            $productId,
            $this->session->get('user/id'),
            $message
        );
    }
}