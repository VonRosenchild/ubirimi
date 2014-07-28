<?php

namespace Ubirimi\Service;

use Ubirimi\PasswordHash;

class PasswordService
{
    /**
     * @var \Ubirimi\PasswordHash;
     */
    private $hasher;

    public function __construct()
    {
        $this->hasher = new PasswordHash(8, false);
    }

    public function check($plainPassword, $hashedPassword)
    {
        return $this->hasher->CheckPassword($plainPassword, $hashedPassword);
    }

    public function hash($plainPassword)
    {
        return $this->hasher->HashPassword($plainPassword);
    }
}
