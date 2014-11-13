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

namespace Ubirimi\Yongo\Controller\Administration\Screen\Scheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;
use Ubirimi\Yongo\Repository\Screen\Screen;
use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyName = false;

        $allScreens = $this->getRepository(Screen::class)->getAll($session->get('client/id'));
        $allOperations = SystemOperation::getAll();

        if ($request->request->has('new_screen_scheme')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $screenId = Util::cleanRegularInputField($request->request->get('screen'));
            $currentDate = Util::getServerCurrentDateTime();

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $screenScheme = new ScreenScheme($session->get('client/id'), $name, $description);
                $screenSchemeId = $screenScheme->save($currentDate);
                while ($operation = $allOperations->fetch_array(MYSQLI_ASSOC)) {
                    $operationId = $operation['id'];
                    $this->getRepository(ScreenScheme::class)->addData($screenSchemeId, $operationId, $screenId, $currentDate);
                }

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('client/id'),
                    'ADD Yongo Screen Scheme ' . $name,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/screens/schemes');
            }
        }
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Screen Scheme';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/screen/scheme/Add.php', get_defined_vars());
    }
}
