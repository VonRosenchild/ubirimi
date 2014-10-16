<?php

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;

use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $calendarId = $request->request->get('id');
        $calendar = $this->getRepository('calendar.calendar.calendar')->getById($calendarId);

        $date = Util::getServerCurrentDateTime();

        $this->getRepository('calendar.calendar.calendar')->deleteById($calendarId);

        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_CALENDAR,
            $session->get('user/id'),
            'DELETE EVENTS calendar ' . $calendar['name'],
            $date
        );

        if ($calendar['default_flag']) {
            // create the default calendar again
            $this->getRepository('calendar.calendar.calendar')->save(
                $session->get('user/id'),
                $session->get('user/first_name') . ' ' . $session->get('user/last_name'),
                'The primary calendar',
                '#A1FF9E',
                $date,
                1
            );
        }

        return new Response('');
    }
}
