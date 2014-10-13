<?php

namespace Ubirimi\Service;

use Ubirimi\Repository\General\Log;

class LogService extends UbirimiService
{
    public function log($productId, $message)
    {
        Log::add(
            $this->session->get('client/id'),
            $productId,
            $this->session->get('user/id'),
            $message
        );
    }
}