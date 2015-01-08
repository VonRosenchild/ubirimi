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

class ConfigureController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $screenId = $request->get('id');

        $screenMetadata = $this->getRepository(Screen::class)->getMetaDataById($screenId);
        if ($screenMetadata['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $position = $request->get('position');
        $fieldId = $request->get('field_id');

        if ($fieldId && $position) {
            $this->getRepository(Screen::class)->updatePositionForField($screenId, $fieldId, $position);

            return new RedirectResponse('/yongo/administration/screen/configure/' . $screenId);
        }

        $fields = $this->getRepository(Field::class)->getByClient($session->get('client/id'));

        if ($request->request->has('add_screen_field')) {
            $fieldId = Util::cleanRegularInputField($request->request->get('field'));

            if ($fieldId != -1) {
                $currentDate = Util::getServerCurrentDateTime();
                $lastOrder = $this->getRepository(Screen::class)->getLastOrderNumber($screenId);
                $this->getRepository(Screen::class)->addData($screenId, $fieldId, ($lastOrder + 1), $currentDate);

                $this->getLogger()->addInfo('UPDATE Yongo Screen Data ' . $screenMetadata['name'], $this->getLoggerContext());

                return new RedirectResponse('/yongo/administration/screen/configure/' . $screenId);
            }
        }

        $screenData = $this->getRepository(Screen::class)->getDataById($screenId);
        $menuSelectedCategory = 'issue';

        $source = $request->get('source');
        $projectId = null;
        if ($source == 'project_screen' || $source == 'project_field') {
            $projectId = $request->get('project_id');
        }
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Screen';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/screen/Configure.php', get_defined_vars());
    }
}
