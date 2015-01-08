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


class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueTypeScreenSchemeId = $request->get('id');
        $issueTypeScreenScheme = $this->getRepository(IssueTypeScreenScheme::class)->getMetaDataById($issueTypeScreenSchemeId);

        if ($issueTypeScreenScheme['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('copy_issue_type_screen_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $duplicateIssueTypeScreenScheme = $this->getRepository(IssueTypeScreenScheme::class)->getMetaDataByNameAndClientId($session->get('client/id'), mb_strtolower($name));
            if ($duplicateIssueTypeScreenScheme)
                $duplicateName = true;

            if (!$emptyName && !$duplicateName) {
                $copiedIssueTypeScreenScheme = new IssueTypeScreenScheme($session->get('client/id'), $name, $description);

                $currentDate = Util::getServerCurrentDateTime();
                $copiedIssueTypeScreenSchemeId = $copiedIssueTypeScreenScheme->save($currentDate);

                $issueTypeScreenSchemeData = $this->getRepository(IssueTypeScreenScheme::class)->getDataByIssueTypeScreenSchemeId($issueTypeScreenSchemeId);

                while ($issueTypeScreenSchemeData && $data = $issueTypeScreenSchemeData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedIssueTypeScreenScheme->addDataComplete($copiedIssueTypeScreenSchemeId, $data['issue_type_id'], $data['screen_scheme_id'], $currentDate);
                }

                $this->getLogger()->addInfo('Copy Yongo Issue Type Scheme ' . $issueTypeScreenScheme['name'], $this->getLoggerContext());

                return new RedirectResponse('/yongo/administration/screens/issue-types');
            }
        }
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Issue Type Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/screen/issue_type_scheme/Copy.php', get_defined_vars());
    }
}
