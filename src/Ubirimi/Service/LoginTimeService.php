<?php

namespace Ubirimi\LoginTimeService;

use Ubirimi\Repository\Client;
use Ubirimi\Util;
use Ubirimi\Repository\User\User;

class LoginTimeService
{
    public function clientSaveLoginTime($clientId)
    {
        $datetime = Util::getServerCurrentDateTime();

        Client::updateLoginTime($clientId, $datetime);
    }

    public function userSaveLoginTime($userId)
    {
        $datetime = Util::getServerCurrentDateTime();

        User::updateLoginTime($userId, $datetime);
    }
}