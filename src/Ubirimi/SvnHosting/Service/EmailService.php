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

namespace Ubirimi\SvnHosting\Service;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Service\UbirimiService;
use Ubirimi\Util;

class EmailService extends UbirimiService
{
    public function newUser($repositoryName, $firstName, $lastName, $username, $mail, $repositoryName)
    {
        if ($this->session->get('client/settings/smtp')) {

            Email::$smtpSettings = $this->session->get('client/settings/smtp');
            UbirimiContainer::get()['repository']->get(Email::class)->sendNewUserRepositoryNotificationEmail($this->session->get('client/id'), $firstName, $lastName, $username, null, $mail, $repositoryName);
        }
    }

    public function passwordUpdate($repositoryName, $user, $password)
    {
        if ($this->session->get('client/settings/smtp')) {
            Email::$smtpSettings = $this->session->get('client/settings/smtp');

            UbirimiContainer::get()['repository']->get(Email::class)->sendUserChangedPasswordForRepositoryNotificationEmail(
                $this->session->get('client/id'),
                $user['first_name'],
                $user['last_name'],
                $user['username'],
                $password,
                $user['email'],
                $repositoryName
            );
        }
    }

    public function importUsers($repositoryName, $user)
    {
        if ($this->session->get('client/settings/smtp')) {
            Email::$smtpSettings = $this->session->get('client/settings/smtp');
            UbirimiContainer::get()['repository']->get(Email::class)->sendNewUserRepositoryNotificationEmail(
                $this->session->get('client/id'),
                $user['first_name'],
                $user['last_name'],
                $user['username'],
                null,
                $user['email'],
                $repositoryName
            );
        }
    }
}