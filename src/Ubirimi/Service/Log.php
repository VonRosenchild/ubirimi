<?php

namespace Ubirimi\Service;

class Log extends UbirimiService
{
    public function log($productId, $message)
    {
        $this->apiClient->post('/log/', array(
                'clientId' => $this->session->get('client/id'),
                'productId' => $productId,
                'userId' => $this->session->get('user/id'),
                'message' => $message
            )
        );
    }
}