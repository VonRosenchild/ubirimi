<?php

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class IndexController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $session->set('selected_product_id', -1);
        $menuSelectedCategory = 'general_home';

        $today = date('Y-m-d');
        $lastWeekToday = date('Y-m-d', strtotime('-1 week'));

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / GeneralSettings Settings / Home';

        return $this->render(__DIR__ . '/../Resources/views/Index.php', get_defined_vars());

    }
}