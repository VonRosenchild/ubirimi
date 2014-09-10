<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);
        $menuSelectedCategory = 'calendars';
        $calendars = Calendar::getByUserId($session->get('user/id'));

        $month = date('n');
        $year = date('Y');

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / My Calendar';

        return $this->render(__DIR__ . '/../Resources/views/List.php', get_defined_vars());
    }
}