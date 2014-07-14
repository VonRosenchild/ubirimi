<?php

namespace Ubirimi\Service;

use Ubirimi\Repository\Log as LogRepository;

class LogService extends UbirimiService
{
    public function log($productId, $message)
    {
        LogRepository::add(
            $this->session->get('client/id'),
            $productId,
            $this->session->get('user/id'),
            $message
        );
    }
}