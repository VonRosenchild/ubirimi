<?php

namespace Ubirimi\LoginTimeService;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Util;

class LoginTimeService
{
    public function clientSaveLoginTime($clientId)
    {
        $datetime = Util::getServerCurrentDateTime();

        UbirimiContainer::get()['repository']->get(UbirimiClient::class)->updateLoginTime($clientId, $datetime);
    }

    public function userSaveLoginTime($userId)
    {
        $datetime = Util::getServerCurrentDateTime();

        UbirimiContainer::get()['repository']->get(UbirimiUser::class)->updateLoginTime($userId, $datetime);
    }
}