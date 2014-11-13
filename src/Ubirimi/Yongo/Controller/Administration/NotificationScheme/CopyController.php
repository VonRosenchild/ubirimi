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
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;

class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $notificationSchemeId = $request->get('id');
        $notificationScheme = $this->getRepository(NotificationScheme::class)->getMetaDataById($notificationSchemeId);

        if ($notificationScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('copy_notification_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $duplicateNotificationScheme = $this->getRepository(NotificationScheme::class)->getMetaDataByNameAndClientId(
                $session->get('client/id'),
                mb_strtolower($name)
            );

            if ($duplicateNotificationScheme)
                $duplicateName = true;

            if (!$emptyName && !$duplicateName) {
                $copiedNotificationScheme = new NotificationScheme($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $copiedNotificationSchemeId = $copiedNotificationScheme->save($currentDate);

                $notificationSchemeData = $this->getRepository(NotificationScheme::class)->getDataByNotificationSchemeId($notificationSchemeId);
                while ($notificationSchemeData && $data = $notificationSchemeData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedNotificationScheme->addDataRaw(
                        $copiedNotificationSchemeId,
                        $data['event_id'],
                        $data['permission_role_id'],
                        $data['group_id'],
                        $data['user_id'],
                        $data['current_assignee'],
                        $data['reporter'],
                        $data['current_user'],
                        $data['project_lead'],
                        $data['component_lead'],
                        $currentDate
                    );
                }

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'Copy Yongo Notification Scheme ' . $notificationScheme['name'],
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/notification-schemes');
            }
        }
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Notification Scheme';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/notification_scheme/Copy.php', get_defined_vars());
    }
}