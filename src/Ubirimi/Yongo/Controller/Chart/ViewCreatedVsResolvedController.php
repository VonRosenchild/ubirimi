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

namespace Ubirimi\Yongo\Controller\Chart;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewCreatedVsResolvedController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $clientId = $session->get('client/id');

        $helpdeskFlag = 0;
        $loggedInUser = Util::checkUserIsLoggedIn();
        if ($loggedInUser) {
            if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_HELP_DESK) {
                $helpdeskFlag = 1;
            }
        }

        $projectId = $request->request->get('id');

        $date = date("Y-m-d", strtotime("-1 month"));
        $date = date('Y-m-d', strtotime("+1 day", strtotime($date)));

        $dateWithoutYear = date("d M", strtotime("-1 month"));
        $currentDate = date("Y-m-d");

        $result = array();
        while (strtotime($date) <= strtotime($currentDate)) {
            $countAllIssues = $this->getRepository(YongoProject::class)->getTotalIssuesPreviousDate($clientId, $projectId, $date, $helpdeskFlag);
            $resolvedCount = $this->getRepository(YongoProject::class)->getTotalIssuesResolvedOnDate($clientId, $projectId, $date, $helpdeskFlag);

            $result[] = array($dateWithoutYear, $countAllIssues, $resolvedCount);
            $dateWithoutYear = date('d M', strtotime("+1 day", strtotime($date)));
            $date = date('Y-m-d', strtotime("+1 day", strtotime($date)));
        }

        return new JsonResponse($result);
    }
}