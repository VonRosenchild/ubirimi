<?php

use Ubirimi\Repository\Client;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\Repository\User\User;
use Ubirimi\Util;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Issue\Issue;


/* check locking mechanism */
if (file_exists('remind_due_date.lock')) {
    $fp = fopen('remind_due_date.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for remind_due_date task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../web/bootstrap_cli.php';

$issues = Issue::getIssuesWithDueDateReminder();


$emailSubject = ''
while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {

    Email::$smtpSettings = SMTPServer::getByClientId($issue['client_id']);

    $emailBody = Util::getTemplate('_remind_due_date.php', array(
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