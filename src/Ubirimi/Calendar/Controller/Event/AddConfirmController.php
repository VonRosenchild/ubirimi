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

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientId = $session->get('client/id');
        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);

        $calendars = $this->getRepository(UbirimiCalendar::class)->getByUserId($session->get('user/id'), 'array');
        $defaultDay = $request->get('day');
        $defaultMonth = $request->get('month');
        $defaultYear = $request->get('year');

        $firstCalendar = $calendars[0];
        if ($defaultDay < 10) {
            $defaultDay = '0' . $defaultDay;
        }
        if ($defaultMonth < 10) {
            $defaultMonth = '0' . $defaultMonth;
        }
        $defaultEventStartDate = $defaultYear . '-' . $defaultMonth . '-' . $defaultDay . ' 00:00';

        $startDate = new \DateTime($defaultYear . '-' . $defaultMonth . '-' . $defaultDay, new \DateTimeZone($clientSettings['timezone']));
        $dayInWeek = date_format($startDate, 'w');

        return $this->render(__DIR__ . '/../../Resources/views/event/AddConfirm.php', get_defined_vars());
    }
}