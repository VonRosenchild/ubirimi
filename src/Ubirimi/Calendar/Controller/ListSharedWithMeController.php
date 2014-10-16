<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar\Calendar;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListSharedWithMeController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);
        $menuSelectedCategory = 'calendars';

        $calendarsSharedWithMe = $this->getRepository('calendar.calendar.calendar')->getSharedWithUserId($session->get('user/id'));

        $month = date('n');
        $year = date('Y');

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / Calendars Shared With Me';

        return $this->render(__DIR__ . '/../Resources/views/ListSharedWithMe.php', get_defined_vars());
    }
}
