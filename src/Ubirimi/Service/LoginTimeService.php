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

        $this->getRepository('ubirimi.general.client')->updateLoginTime($clientId, $datetime);
    }

    public function userSaveLoginTime($userId)
    {
        $datetime = Util::getServerCurrentDateTime();

        $this->getRepository('ubirimi.user.user')->updateLoginTime($userId, $datetime);
    }
}