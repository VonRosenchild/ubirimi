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

namespace Ubirimi\Yongo\Controller\Administration\PermissionScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;

class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionSchemeId = $request->get('id');
        $permissionScheme = $this->getRepository(PermissionScheme::class)->getMetaDataById($permissionSchemeId);

        if ($permissionScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('copy_permission_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            $duplicatePermissionScheme = $this->getRepository(PermissionScheme::class)->getMetaDataByNameAndClientId(
                $session->get('client/id'),
                mb_strtolower($name)
            );

            if ($duplicatePermissionScheme)
                $duplicateName = true;

            if (!$emptyName && !$duplicateName) {
                $copiedPermissionScheme = new PermissionScheme($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $copiedPermissionSchemeId = $copiedPermissionScheme->save($currentDate);

                $permissionSchemeData = $this->getRepository(PermissionScheme::class)->getDataByPermissionSchemeId($permissionSchemeId);

                while ($permissionSchemeData && $data = $permissionSchemeData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedPermissionScheme->addDataRaw(
                        $copiedPermissionSchemeId,
                        $data['sys_permission_id'],
                        $data['permission_role_id'],
                        $data['group_id'],
                        $data['user_id'],
                        $data['current_assignee'],
                        $data['reporter'],
                        $data['project_lead'],
                        $currentDate
                    );
                }

                $this->getLogger()->addInfo('Copy Yongo Permission Scheme ' . $permissionScheme['name'], $this->getLoggerContext());

                return new RedirectResponse('/yongo/administration/permission-schemes');
            }
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Permission Scheme';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/permission_scheme/Copy.php', get_defined_vars());
    }
}
