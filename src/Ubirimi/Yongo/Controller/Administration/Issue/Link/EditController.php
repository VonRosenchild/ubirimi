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

namespace Ubirimi\Yongo\Controller\Administration\Issue\Link;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\LinkType;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $linkTypeId = $request->get('id');

        $emptyName = false;
        $emptyOutwardDescription = false;
        $emptyInwardDescription = false;
        $linkTypeDuplicateName = false;

        $linkType = LinkType::getById($linkTypeId);

        if ($linkType['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $name = $linkType['name'];
        $outwardDescription = $linkType['outward_description'];
        $inwardDescription = $linkType['inward_description'];

        if ($request->request->has('edit_link_type')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $outwardDescription = Util::cleanRegularInputField($request->request->get('outward'));
            $inwardDescription = Util::cleanRegularInputField($request->request->get('inward'));

            if (empty($name))
                $emptyName = true;

            if (empty($outwardDescription))
                $emptyOutwardDescription = true;

            if (empty($inwardDescription))
                $emptyInwardDescription = true;

            // check for duplication
            $existingLinkType = LinkType::getByNameAndClientId(
                $session->get('client/id'),
                mb_strtolower($name),
                $linkTypeId
            );

            if ($existingLinkType)
                $linkTypeDuplicateName = true;

            if (!$emptyName && !$emptyOutwardDescription && !$emptyInwardDescription && !$linkTypeDuplicateName) {
                $currentDate = Util::getServerCurrentDateTime();
                LinkType::update($linkTypeId, $name, $outwardDescription, $inwardDescription, $currentDate);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Issue Link Type ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/issue-features/linking');
            }
        }

        $menuSelectedCategory = 'system';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Link Type';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/link/Edit.php', get_defined_vars());
    }
}
