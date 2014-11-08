<?php

namespace Ubirimi\General\Controller\SMTP;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', -1);
        $menuSelectedCategory = 'general_mail';

        $smtpSettings = $this->getRepository(SMTPServer::class)->getByClientId($session->get('client/id'));

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / GeneralSettings Settings / SMTP Server Settings';

        return $this->render(__DIR__ . '/../../Resources/views/smtp/View.php', get_defined_vars());
    }
}
