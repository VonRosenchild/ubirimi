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

namespace Ubirimi\Yongo\Controller\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueStatistic;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;

class ViewSummaryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $session->get('selected_project_id');
        $userId = $request->get('id');
        $loggedInUserId = $session->get('user/id');
        $user = $this->getRepository(UbirimiUser::class)->getById($userId);

        if ($user['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }
        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($session->get('client/id'));

        $groups = $this->getRepository(UbirimiGroup::class)->getByUserIdAndProductId($userId, SystemProduct::SYS_PRODUCT_YONGO);
        $stats = $this->getRepository(IssueStatistic::class)->getUnresolvedIssuesByProjectForUser($userId);

        $hasAdministrationGlobalPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission($session->get('client/id'), $userId, GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
        $hasSystemAdministrationGlobalPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission($session->get('client/id'), $userId, GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);

        $projectsForBrowsing = $this->getRepository(UbirimiClient::class)->getProjectsByPermission($session->get('client/id'), $session->get('user/id'), Permission::PERM_BROWSE_PROJECTS, 'array');
        $projectIds = array();

        if ($projectsForBrowsing) {
            $projectIds = Util::array_column($projectsForBrowsing, 'id');
        }
        $menuSelectedCategory = 'user';

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

        $hoursPerDay = $session->get('yongo/settings/time_tracking_hours_per_day');
        $daysPerWeek = $session->get('yongo/settings/time_tracking_days_per_week');

        $historyList = Util::getProjectHistory($projectIds, 0, $userId);
        $historyData = array();
        $userData = array();
        while ($historyList && $history = $historyList->fetch_array(MYSQLI_ASSOC)) {
            $historyData[substr($history['date_created'], 0, 10)][$history['user_id']][$history['date_created']][] = $history;
            $userData[$history['user_id']] = array('picture' => $history['avatar_picture'],
                                                   'first_name' => $history['first_name'],
                                                   'last_name' => $history['last_name']);
        }
        $index = 0;
        $startDate = date_sub(new \DateTime(Util::getServerCurrentDateTime(), new \DateTimeZone($clientSettings['timezone'])), date_interval_create_from_date_string('1 months'));

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / User: ' . $user['first_name'] . ' ' . $user['last_name'] . ' / Profile';

        return $this->render(__DIR__ . '/../../Resources/views/user/ViewSummary.php', get_defined_vars());
    }
}
