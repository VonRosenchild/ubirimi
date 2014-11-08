<?php

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ManageApplicationsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $session->set('selected_product_id', -1);
        $menuSelectedCategory = 'general_home';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / GeneralSettings Settings / Manage Applications';

        $productsArray = $session->get('client/products');

        return $this->render(__DIR__ . '/../Resources/views/ManageApplications.php', get_defined_vars());
    }
}