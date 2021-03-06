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

namespace Ubirimi\Yongo\Controller\Administration\Issue\SecurityScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
use Ubirimi\Yongo\Repository\Permission\Role;


class AddLevelDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $levelId = $request->get('id');

        $level = $this->getRepository(IssueSecurityScheme::class)->getLevelById($levelId);
        $issueSecurityScheme = $this->getRepository(IssueSecurityScheme::class)->getMetaDataById($level['issue_security_scheme_id']);

        if ($issueSecurityScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $users = $this->getRepository(UbirimiUser::class)->getByClientId($session->get('client/id'));
        $groups = $this->getRepository(UbirimiGroup::class)->getByClientIdAndProductId($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO);
        $roles = $this->getRepository(Role::class)->getByClient($session->get('client/id'));

        if ($request->request->has('confirm_new_data')) {

            $levelDataType = $request->request->get('type');

            $user = $request->request->get('user');
            $group = $request->request->get('group');
            $role = $request->request->get('role');
            $currentDate = Util::getServerCurrentDateTime();

            if ($levelDataType) {

                // check for duplicate information
                $duplication = false;
                $dataLevel = $this->getRepository(IssueSecurityScheme::class)->getDataByLevelId($levelId);

                if ($dataLevel) {

                    while ($data = $dataLevel->fetch_array(MYSQLI_ASSOC)) {
                        if ($data['group_id'] && $data['group_id'] == $group)
                            $duplication = true;
                        if ($data['user_id'] && $data['user_id'] == $user)
                            $duplication = true;
                        if ($data['permission_role_id'] && $data['permission_role_id'] && $role)
                            $duplication = true;

                        if ($levelDataType == IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_PROJECT_LEAD)
                            if ($data['project_lead'])
                                $duplication = true;
                        if ($levelDataType == IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_CURRENT_ASSIGNEE)
                            if ($data['current_assignee'])
                                $duplication = true;
                        if ($levelDataType == IssueSecurityScheme::SECURITY_SCHEME_DATA_TYPE_REPORTER)
                            if ($data['reporter'])
                                $duplication = true;
                    }
                }
                if (!$duplication) {
                    $this->getRepository(IssueSecurityScheme::class)->addLevelData($levelId, $levelDataType, $user, $group, $role, $currentDate);

                    $this->getLogger()->addInfo('UPDATE Yongo Issue Security Scheme Level ' . $level['name'], $this->getLoggerContext());
                }
            }

            return new RedirectResponse('/yongo/administration/issue-security-scheme-levels/' . $issueSecurityScheme['id']);
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Security Scheme Level Data';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/AddLevelData.php', get_defined_vars());
    }
}
