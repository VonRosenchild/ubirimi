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

namespace Ubirimi\Yongo\Controller\Administration\Screen;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Screen\Screen;

class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $screenId = $request->get('id');
        $screen = $this->getRepository(Screen::class)->getMetaDataById($screenId);

        if ($screen['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyScreenName = false;
        $screenExists = false;

        if ($request->request->has('edit_workflow_screen')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyScreenName = true;

            // check for duplication
            $screen_row_exists = $this->getRepository(Screen::class)->getByName($session->get('client/id'), mb_strtolower($name));

            if ($screen_row_exists)
                $screenExists = true;

            if (!$screenExists && !$emptyScreenName) {
                $copiedScreen = new Screen($session->get('client/id'), $name, $description);
                $currentDate = Util::getServerCurrentDateTime();
                $copiedScreenId = $copiedScreen->save($currentDate);

                $screenData = $this->getRepository(Screen::class)->getDataById($screenId);
                while ($data = $screenData->fetch_array(MYSQLI_ASSOC)) {
                    $this->getRepository(Screen::class)->addData($copiedScreenId, $data['field_id'], $data['position'], $currentDate);
                }

                return new RedirectResponse('/yongo/administration/screens');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Screen';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/screen/Copy.php', get_defined_vars());
    }
}
