<?php

namespace Ubirimi\Yongo\Controller\Administration\GeneralConfiguration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Client;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientYongoSettings = $this->getRepository('ubirimi.general.client')->getYongoSettings($session->get('client/id'));
        $menuSelectedCategory = 'system';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/general_configuration/View.php', get_defined_vars());
    }
}
