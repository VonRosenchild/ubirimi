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
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Screen\Screen;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $menuSelectedCategory = 'issue';
        $emptyName = false;

        $fields = $this->getRepository(Field::class)->getByClient($session->get('client/id'));

        if ($request->request->has('add_screen')) {

            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $currentDate = Util::getServerCurrentDateTime();

            if (empty($name))
                $emptyName = true;

            if (!$emptyName) {
                $screen = new Screen($session->get('client/id'), $name, $description);
                $screenId = $screen->save($currentDate);

                $order = 0;
                foreach ($request->request as $key => $value) {
                    if (substr($key, 0, 6) == 'field_') {
                        $order++;
                        $fieldId = str_replace('field_', '', $key);
                        $this->getRepository(Screen::class)->addData($screenId, $fieldId, $order, $currentDate);
                    }
                }

                $this->getLogger()->addInfo('ADD Yongo Screen ' . $name, $this->getLoggerContext());

                return new RedirectResponse('/yongo/administration/screens');
            }
        }
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Screen';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/screen/Add.php', get_defined_vars());
    }
}
