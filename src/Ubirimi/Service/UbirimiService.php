<?php

namespace Ubirimi\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Component\ApiClient\ApiClient;

class UbirimiService
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
}