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

namespace Ubirimi\Service;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\Email as EmailRepository;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\Util;

class EmailService extends UbirimiService
{
    public function newUser($firstName, $lastName, $username, $password, $email, $clientDomain, $clientId)
    {
        $smtpSettings = UbirimiContainer::get()['repository']->get(SMTPServer::class)->getByClientId($clientId);
        if ($smtpSettings) {
            EmailRepository::$smtpSettings = $smtpSettings;
        } else {
            EmailRepository::$smtpSettings = Util::getUbirimiSMTPSettings();
        }

        UbirimiContainer::get()['repository']->get(EmailRepository::class)->sendNewUserNotificationEmail($clientId, $firstName, $lastName, $username, $password, $email, $clientDomain);
    }

    public function newUserCustomer($firstName, $lastName, $password, $email, $clientDomain, $clientId)
    {

        $smtpSettings = UbirimiContainer::get()['repository']->get(SMTPServer::class)->getByClientId($clientId);
        if ($smtpSettings) {
            EmailRepository::$smtpSettings = $smtpSettings;
        } else {
            EmailRepository::$smtpSettings = Util::getUbirimiSMTPSettings();
        }

        UbirimiContainer::get()['repository']->get(EmailRepository::class)->sendNewCustomerNotificationEmail($clientId, $firstName, $lastName, $email, $password, $clientDomain);
    }

    public function feedback($userData, $like, $improve, $newFeatures, $experience)
    {
        UbirimiContainer::get()['repository']->get(EmailRepository::class)->sendFeedback($userData, $like, $improve, $newFeatures, $experience);
    }

    public function passwordRecover($email, $password)
    {
        UbirimiContainer::get()['repository']->get(EmailRepository::class)->sendEmailRetrievePassword($email, $password);
    }
}