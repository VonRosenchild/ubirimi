<?php

namespace Ubirimi\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

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