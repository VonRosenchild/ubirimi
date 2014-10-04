<?php
use Ubirimi\Repository\Email\EmailQueue;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\Util;

/* check locking mechanism */
if (file_exists(__DIR__ . '/process_mail_queue.lock')) {
    $fp = fopen('process_mail_queue.lock', 'w+');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        echo "Unable to obtain lock for process_mail_queue task.\n";
        exit(-1);
    }
}

require_once __DIR__ . '/../web/bootstrap_cli.php';

$emails = EmailQueue::getBatch();

while ($emails && $email = $emails->fetch_array(MYSQLI_ASSOC)) {
    $smtpSettings = SMTPServer::getByClientId($email['client_id']);
    if (null == $smtpSettings) {
        $smtpSettings = Util::getUbirimiSMTPSettings();
    }

    try {
        echo 'Process email Id: ' . $email['id'] . "\n";
        EmailQueue::send($smtpSettings, $email);
        EmailQueue::deleteById($email['id']);
    } catch (Swift_TransportException $e) {
        echo $e->getMessage() . "\n";
    } catch (Swift_IoException $e) {
        echo $e->getMessage() . "\n";
    } catch (Swift_RfcComplianceException $e) {
        echo $e->getMessage() . "\n";
    } catch (\Exception $e) {
        echo $e->getMessage() . "\n";
    }

}

if (null !== $fp) {
    fclose($fp);
}