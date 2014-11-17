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
use Ubirimi\Yongo\Repository\Issue\IssueLinkType;


class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;
        $emptyOutwardDescription = false;
        $emptyInwardDescription = false;
        $linkTypeDuplicateName = false;

        if ($request->request->has('new_link_type')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $outwardDescription = Util::cleanRegularInputField($request->request->get('outward'));
            $inwardDescription = Util::cleanRegularInputField($request->request->get('inward'));

            if (empty($name)) {
                $emptyName = true;
            }

            if (empty($outwardDescription)) {
                $emptyOutwardDescription = true;
            }

            if (empty($inwardDescription)) {
                $emptyInwardDescription = true;
            }

            // check for duplication
            $linkType = $this->getRepository(IssueLinkType::class)->getByNameAndClientId($session->get('client/id'), mb_strtolower($name));

            if ($linkType)
                $linkTypeDuplicateName = true;

            if (!$emptyName && !$emptyOutwardDescription && !$emptyInwardDescription && !$linkTypeDuplicateName) {
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository(IssueLinkType::class)->add(
                    $session->get('client/id'),
                    $name,
                    $outwardDescription,
                    $inwardDescription,
                    $currentDate
                );

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Issue Link Type',
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/issue-features/linking');
            }
        }

        $menuSelectedCategory = 'system';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Link Type';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/link/Add.php', get_defined_vars());
    }
}
