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

namespace Ubirimi\Yongo\Controller\Administration\Screen\IssueTypeScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

class EditDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueTypeScreenSchemeDataId = $request->get('id');
        $screenSchemes = $this->getRepository(ScreenScheme::class)->getMetaDataByClientId($session->get('client/id'));
        $issueTypeScreenSchemeData = $this->getRepository(IssueTypeScreenScheme::class)->getDataById($issueTypeScreenSchemeDataId);

        $screenSchemeId = $issueTypeScreenSchemeData['issue_type_screen_scheme_id'];
        $issueTypeScreenSchemeMetaData = $this->getRepository(IssueTypeScreenScheme::class)->getMetaDataById($screenSchemeId);

        if ($issueTypeScreenSchemeMetaData['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        if ($request->request->has('edit_issue_type_screen_scheme_data')) {
            $currentDate = Util::getServerCurrentDateTime();

            $screenSchemeId = Util::cleanRegularInputField($request->request->get('screen_scheme'));
            $issueTypeId = Util::cleanRegularInputField($request->request->get('issue_type'));

            $this->getRepository(IssueTypeScreenScheme::class)->updateDataById($screenSchemeId, $issueTypeId, $issueTypeScreenSchemeMetaData['id']);

            $this->getLogger()->addInfo('UPDATE Yongo Issue Type Screen Scheme Data ' . $issueTypeScreenSchemeMetaData['name'], $this->getLoggerContext());

            return new RedirectResponse('/yongo/administration/screen/configure-scheme-issue-type/' . $issueTypeScreenSchemeMetaData['id']);
        }
        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Type Screen Scheme Data';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/screen/issue_type_scheme/EditData.php', get_defined_vars());
    }
}
