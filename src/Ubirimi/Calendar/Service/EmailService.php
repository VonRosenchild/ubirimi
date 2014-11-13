<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

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