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

namespace Ubirimi\Yongo\Controller\Issue\Filter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;

class AddSubscriptionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $minute = $request->request->get('minute');
        $hour = $request->request->get('hour');
        $day = $request->request->get('day');
        $month = $request->request->get('month');
        $weekday = $request->request->get('weekday');

        $filterId = $request->request->get('id');
        $recipientId = $request->request->get('recipient_id');
        $emailWhenEmptyFlag = $request->request->get('email_when_empty');

        $userId = null;
        $groupId = null;
        if (substr($recipientId, 0, 1) == 'u') {
            $userId = substr($recipientId, 1);
        } else {
            $groupId = substr($recipientId, 1);
        }
        $cronExpression = implode(',', $minute) . ' ' . implode(',', $hour) . ' ' . implode(',', $day) . ' ' . implode(',', $month) . ' ' . implode(',', $weekday);

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository(IssueFilter::class)->addSubscription($filterId, $loggedInUserId, $userId, $groupId, $cronExpression, $emailWhenEmptyFlag, $currentDate);

        return new Response('');
    }
}