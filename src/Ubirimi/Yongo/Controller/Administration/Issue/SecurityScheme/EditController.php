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
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $issueSecuritySchemeId = $request->get('id');
        $issueSecurityScheme = $this->getRepository(IssueSecurityScheme::class)->getMetaDataById($issueSecuritySchemeId);

        if ($issueSecurityScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        if ($request->request->has('edit_issue_security_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $this->getRepository(IssueSecurityScheme::class)->updateMetaDataById($issueSecuritySchemeId, $name, $description);

                return new RedirectResponse('/yongo/administration/issue-security-schemes');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Security Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/security_scheme/Edit.php', get_defined_vars());
    }
}
