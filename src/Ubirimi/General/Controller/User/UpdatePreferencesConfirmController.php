<?php

namespace Ubirimi\General\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\User\User;

class UpdatePreferencesConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $settings = $this->getRepository('ubirimi.user.user')->getYongoSettings($session->get('user/id'));
        $countries = Util::getCountries();

        return $this->render(__DIR__ . '/../../Resources/views/user/UpdatePreferencesConfirm.php', get_defined_vars());
    }
}
