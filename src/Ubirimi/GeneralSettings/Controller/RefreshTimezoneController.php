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

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class RefreshTimezoneController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $zone = $request->request->get('zone');

        $zones = \DateTimeZone::listIdentifiers($zone);
        for ($i = 0; $i < count($zones); $i++) {
            $dateTimeZone0 = new \DateTimeZone($zones[$i]);
            $dateTime0 = new \DateTime("now", $dateTimeZone0);
            $timeOffset = $dateTimeZone0->getOffset($dateTime0);
            $prefix = ($timeOffset > 0) ? '+' : '';
            $data = explode("/", $zones[$i]);
            $prefixContinent = $data[0];
            $offset = (($timeOffset / 60 / 60));

            if ($offset < 0) {
                $offset++;
            } else {
                $offset--;
            }

            return new Response('<option value="' . $zones[$i] . '">' . str_replace(array($prefixContinent . "/", "_"), array("", " "), $zones[$i]) . ' (GMT ' . $prefix . $offset . 'h)</option>');
        }
    }
}