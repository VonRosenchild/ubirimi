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

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\History;

class ViewEntityHistoryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $yongoSettings = $session->get('yongo/settings');
            $clientId = $session->get('client/id');
            $clientSettings = $session->get('client/settings');
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
            $yongoSettings = $this->getRepository(UbirimiClient::class)->getYongoSettings($clientId);
        }

        $issueId = $request->request->get('issue_id');
        $userId = $request->request->get('user_id');
        $projectId = $request->request->get('project_id');
        $historyList = $this->getRepository(History::class)->getByIssueIdAndUserId($issueId, $userId, $projectId);
        $color = null;

        $hoursPerDay = $yongoSettings['time_tracking_hours_per_day'];
        $daysPerWeek = $yongoSettings['time_tracking_days_per_week'];

        return $this->render(__DIR__ . '/../../Resources/views/issue/ViewEntityHistory.php', get_defined_vars());
    }
}