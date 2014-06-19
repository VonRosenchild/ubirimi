<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\Repository\SMTPServer;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $smtpServerId = $_POST['id'];

    $smtpServer = SMTPServer::getById($smtpServerId);
    $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Log::add($clientId, SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS, $loggedInUserId, 'DELETE SMTP Server ' . $smtpServer['name'], $date);

    SMTPServer::deleteById($smtpServerId);
    $session->remove('client/settings/smtp');