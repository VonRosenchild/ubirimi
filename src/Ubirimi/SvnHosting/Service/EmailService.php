<?php

namespace Ubirimi\SVNHosting\Service;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Service\UbirimiService;
use Ubirimi\Util;

class EmailService extends UbirimiService
{
    public function newUser($repositoryName, $firstName, $lastName, $username, $mail, $repositoryName)
    {
        Email::$smtpSettings = Util::getUbirimiSMTPSettings();
        UbirimiContainer::get()['repository']->get(Email::class)->sendNewUserRepositoryNotificationEmail($this->session->get('client/id'), $firstName, $lastName, $username, null, $mail, $repositoryName);
    }

    public function passwordUpdate($repositoryName, $user, $password)
    {
        Email::$smtpSettings = Util::getUbirimiSMTPSettings();
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

    public function importUsers($repositoryName, $user)
    {
        $smtpSettings = $this->session->get('client/settings/smtp');

        if ($smtpSettings) {
            Email::$smtpSettings = $smtpSettings;
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