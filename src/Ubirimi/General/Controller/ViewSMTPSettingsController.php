<?php

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\SMTPServer;

class ViewSMTPSettingsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', -1);
        $menuSelectedCategory = 'general_mail';

        $smtpSettings = SMTPServer::getByClientId($session->get('client/id'));

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / General Settings / SMTP Server Settings';

        return $this->render(__DIR__ . '/../Resources/views/ViewSMTPSettings.php', get_defined_vars());
    }
}
