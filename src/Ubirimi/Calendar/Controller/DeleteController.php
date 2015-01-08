<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $calendarId = $request->request->get('id');
        $calendar = $this->getRepository(UbirimiCalendar::class)->getById($calendarId);

        $date = Util::getServerCurrentDateTime();

        $this->getRepository(UbirimiCalendar::class)->deleteById($calendarId);

        $this->getLogger()->addInfo('DELETE EVENTS calendar ' . $calendar['name'], $this->getLoggerContext());

        if ($calendar['default_flag']) {
            // create the default calendar again
            $this->getRepository(UbirimiCalendar::class)->save(
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
