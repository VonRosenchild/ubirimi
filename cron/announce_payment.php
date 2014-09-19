<?php

use Ubirimi\Repository\Client;
use Ubirimi\Util;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;


/* check locking mechanism */
if (file_exists('announce_payment.lock')) {
    $fp = fopen('announce_payment.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for announce_payment task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../web/bootstrap_cli.php';

$clients = Client::getCurrentMonthPayingCustomers();
/**
 * send the email to every client administrator
 * also send the email to the company contact email address.
 * if this address is the same with one of the client administrators then do not send it again
*/

while ($clients && $client = $clients->fetch_array(MYSQLI_ASSOC)) {
    $clientId = $client['id'];

    $emailSubject = 'Ubirimi Invoice UBR';
    $clientAdministrators = Client::getAdministrators($client['id']);

    $clientAdministratorsEmailAddresses = array();
    $mailer = Util::getUbirmiMailer('contact');

    while ($clientAdministrators && $clientAdministrator = $clientAdministrators->fetch_array(MYSQLI_ASSOC)) {
        $clientAdministratorsEmailAddresses[] = $clientAdministrator['email'];

        $emailBody = Util::getTemplate('_announce_payment.php', array(
            'clientAdministrator' => $clientAdministrator['first_name'] . ' ' . $clientAdministrator['last_name'],
            'clientDomain' => $client['company_domain'],
            'baseUrl' => $client['base_url'])
        );

        $message = Swift_Message::newInstance($emailSubject)
            ->setFrom(array('contact@ubirimi.com'))
            ->setTo($clientAdministrator['email'])
            ->setBody($emailBody, 'text/html');

        try {
            $mailer->send($message);
        } catch (Exception $e) {
            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $client['id'], 'Could not send announce payment email', Util::getServerCurrentDateTime());
        }
    }

    // send the email also to the client company contact email address if necessary
    if (!in_array($client['contact_email'], $clientAdministratorsEmailAddresses)) {

        $emailBody = Util::getTemplate('_announce_payment.php', array(
            'clientAdministrator' => $client['company_domain'],
            'clientDomain' => $client['company_domain'])
        );

        $message = Swift_Message::newInstance($emailSubject)
            ->setFrom(array('contact@ubirimi.com'))
            ->setTo($client['contact_email'])
            ->setBody($emailBody, 'text/html');

        try {
            $mailer->send($message);
        } catch (Exception $e) {
            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $client['id'], 'Could not send announce payment email', Util::getServerCurrentDateTime());
        }
    }
}

if (null !== $fp) {
    fclose($fp);
}