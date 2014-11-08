<?php

namespace Ubirimi\General\Controller\SMTP;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $smtpServerId = $request->request->get('id');

        $smtpServer = $this->getRepository(SMTPServer::class)->getById($smtpServerId);
        $date = Util::getServerCurrentDateTime();

        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS,
            $session->get('user/id'),
            'DELETE SMTP Server ' . $smtpServer['name'],
            $date
        );

        $this->getRepository(SMTPServer::class)->deleteById($smtpServerId);
        $session->remove('client/settings/smtp');

        return new Response('');
    }
}
