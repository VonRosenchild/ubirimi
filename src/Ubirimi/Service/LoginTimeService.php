<?php

namespace Ubirimi\LoginTimeService;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\Util;

class LoginTimeService
{
    public function clientSaveLoginTime($clientId)
    {
        $datetime = Util::getServerCurrentDateTime();

        UbirimiContainer::get()['repository']->get('ubirimi.general.client')->updateLoginTime($clientId, $datetime);
    }

    public function userSaveLoginTime($userId)
    {
        $datetime = Util::getServerCurrentDateTime();

        UbirimiContainer::get()['repository']->get('ubirimi.user.user')->updateLoginTime($userId, $datetime);
    }
}