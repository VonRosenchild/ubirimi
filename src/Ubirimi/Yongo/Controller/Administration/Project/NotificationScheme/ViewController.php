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

namespace Ubirimi\Yongo\Controller\Administration\Project\NotificationScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->get('id');
        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        if ($project['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $notificationSchemeId = $project['notification_scheme_id'];
        $notificationScheme = $this->getRepository(NotificationScheme::class)->getMetaDataById($notificationSchemeId);
        $events = $this->getRepository(IssueEvent::class)->getByClient($session->get('client/id'));

        $hasGlobalAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS
        );

        $hasGlobalSystemAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS
        );

        $menuSelectedCategory = 'project';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Project Notification Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/project/notification_scheme/View.php', get_defined_vars());
    }
}
