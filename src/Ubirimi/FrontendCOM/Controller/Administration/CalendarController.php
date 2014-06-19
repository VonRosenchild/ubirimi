<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\UbirimiController;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Util;

class CalendarController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $calendars = Calendar::getAll();

        $selectedOption = 'calendar';

        return $this->render(__DIR__ . '/../../Resources/views/administration/Calendar.php', get_defined_vars());
    }
}
