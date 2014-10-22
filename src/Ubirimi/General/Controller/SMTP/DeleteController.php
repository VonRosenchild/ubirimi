<?php

namespace Ubirimi\General\Controller\SMTP;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

        $smtpServer = SMTPServer::getById($smtpServerId);
        $date = Util::getServerCurrentDateTime();

        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS,
            $session->get('user/id'),
            'DELETE SMTP Server ' . $smtpServer['name'],
            $date
        );

        SMTPServer::deleteById($smtpServerId);
        $session->remove('client/settings/smtp');

        return new Response('');
    }
}
