<?php

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AboutController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientSettings = $session->get('client/settings');

        $session->set('selected_product_id', -2);

        $menuSelectedCategory = 'ubirimi_about';

        $sectionPageTitle = $clientSettings['title_name'] . ' / About Customer Portal';

        return $this->render(__DIR__ . '/../../Resources/views/customer_portal/About.php', get_defined_vars());
    }
}
