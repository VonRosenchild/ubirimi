<?php

namespace Ubirimi\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Component\ApiClient\ApiClient;

class UbirimiService
{
    /**
     * @var \Ubirimi\Component\ApiClient\ApiClient
     */
    protected $apiClient;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    public function __construct(ApiClient $client, SessionInterface $session)
    {
        $this->apiClient = $client;
        $this->session = $session;
    }
}