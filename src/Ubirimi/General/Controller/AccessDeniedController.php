<?php

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AccessDeniedController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', -1);
        $menuSelectedCategory = 'general_home';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / Something is wrong...';

        return $this->render(__DIR__ . '/../Resources/views/AccessDenied.php', get_defined_vars());

    }
}