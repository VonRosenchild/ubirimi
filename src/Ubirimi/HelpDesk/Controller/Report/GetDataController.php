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

namespace Ubirimi\HelpDesk\Controller\Report;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class GetDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $slaId = $request->get('id');

        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $issues = $this->getRepository(Sla::class)->getIssues($slaId, $dateFrom, $dateTo);

        $dates = array();
        $succeeded = array();
        $breached = array();

        $dateTemporary = new \DateTime($dateFrom, new \DateTimeZone($clientSettings['timezone']));
        while (date_format($dateTemporary, 'Y-m-d') <= $dateTo) {
            $dates[] = date_format($dateTemporary, 'Y-m-d');
            $succeeded[end($dates)] = 0;
            $breached[end($dates)] = 0;
            date_add($dateTemporary, date_interval_create_from_date_string('1 days'));
        }

        while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
            if ($issue['sla_value'] >= 0) {
                $succeeded[substr($issue['stopped_date'], 0, 10)]++;
            } else {
                $breached[substr($issue['stopped_date'], 0, 10)]++;
            }
        }

        $data = array('dates' => $dates, 'succeeded' => $succeeded, 'breached' => $breached);

        return new JsonResponse($data);
    }
}