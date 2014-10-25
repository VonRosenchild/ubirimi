<?php

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

    public function contact($name, $category, $message, $email)
    {
        UbirimiContainer::get()['repository']->get(EmailRepository::class)->sendContactMessage(
            array('domnulnopcea@gmail.com', 'domnuprofesor@gmail.com'),
            $name,
            $category,
            $message,
            $email
        );
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