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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Event\CalendarEvent;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $eventId = $request->request->get('id');
        $recurringType = $request->request->get('recurring');

        // check if it is a shared calendar
        $isSharedEvent = $this->getRepository(CalendarEvent::class)->getShareByUserIdAndEventId($session->get('user/id'), $eventId);

        if ($isSharedEvent) {
            // delete only the share
            $this->getRepository(CalendarEvent::class)->deleteEventSharesByUserId($eventId, c);
        } else {
            // delete the event and the possible shares
            $this->getRepository(CalendarEvent::class)->deleteById($eventId, $recurringType);
        }

        return new Response('');
    }
}
