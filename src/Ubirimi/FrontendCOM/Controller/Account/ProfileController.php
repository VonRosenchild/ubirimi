<?php

namespace Ubirimi\FrontendCOM\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ProfileController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $countries = Util::getCountries();
        $session->remove('profile_updated');

        $errors = array('empty_company_name' => false,
            'empty_contact_email' => false,
            'contact_email_not_valid' => false,
            'contact_email_already_exists' => false,
            'empty_address_1' => false,
            'empty_city' => false,
            'empty_district' => false);

        $clientData = $this->getRepository('ubirimi.general.client')->getById($session->get('client/id'));

        $content = 'account/Profile.php';
        $page = 'account_profile';

        return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
    }
}
