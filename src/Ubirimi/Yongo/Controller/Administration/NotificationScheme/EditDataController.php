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

namespace Ubirimi\Yongo\Controller\Administration\NotificationScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class EditDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $notificationSchemeId = $request->get('id');
        $backLink = $request->get('back');
        $projectId = $request->get('project_id');

        $notificationScheme = $this->getRepository(NotificationScheme::class)->getMetaDataById($notificationSchemeId);
        if ($notificationScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($projectId) {
            $project = $this->getRepository(YongoProject::class)->getById($projectId);
            if ($project['client_id'] != $session->get('client/id')) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }
        }

        $events = $this->getRepository(IssueEvent::class)->getByClient($session->get('client/id'));
        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Notification Scheme';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/notification_scheme/EditData.php', get_defined_vars());
    }
}
