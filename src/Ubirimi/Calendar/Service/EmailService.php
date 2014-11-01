<?php

namespace Ubirimi\Calendar\Service;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Service\UbirimiService;

class EmailService extends UbirimiService
{
    public function shareCalendar($calendar, $userThatShares, $usersToShareWith, $noteContent)
    {
        $smtpSettings = UbirimiContainer::get()['session']->get('client/settings/smtp');

        if ($smtpSettings) {
            Email::$smtpSettings = $smtpSettings;

            for ($i = 0; $i < count($usersToShareWith); $i++) {
                $user = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getById($usersToShareWith[$i]);

                UbirimiContainer::get()['repository']->get(Email::class)->shareCalendar($this->session->get('client/id'), $calendar, $userThatShares, $user['email'], $noteContent);
            }
        }
    }

    public function shareEvent($event, $userThatShares, $usersToShareWith, $noteContent)
    {
        $smtpSettings = UbirimiContainer::get()['session']->get('client/settings/smtp');

        if ($smtpSettings) {
            Email::$smtpSettings = $smtpSettings;

            for ($i = 0; $i < count($usersToShareWith); $i++) {
                $user = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getById($usersToShareWith[$i]);
                UbirimiContainer::get()['repository']->get(Email::class)->shareEvent($this->session->get('client/id'), $event, $userThatShares, $user['email'], $noteContent);
            }
        }
    }
}