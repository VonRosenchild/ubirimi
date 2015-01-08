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

namespace Ubirimi\Yongo\Controller\Administration\Field\Custom;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Field\FieldConfiguration;
use Ubirimi\Yongo\Repository\Screen\Screen;

class EditScreenVisibilityController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $fieldId = $request->get('id');
        $field = $this->getRepository(Field::class)->getById($fieldId);

        if ($field['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $screens = $this->getRepository(Screen::class)->getAll($session->get('client/id'));

        if ($request->request->has('edit_field_custom_screen')) {
            $currentDate = Util::getServerCurrentDateTime();

            while ($screen = $screens->fetch_array(MYSQLI_ASSOC)) {
                $this->getRepository(Screen::class)->deleteDataByScreenIdAndFieldId($screen['id'], $fieldId);
            }

            foreach ($request->request as $key => $value) {
                if (substr($key, 0, 13) == 'field_screen_') {
                    $data = str_replace('field_screen_', '', $key);
                    $values = explode('_', $data);
                    $fieldSelectedId = $values[0];
                    $screenSelectedId = $values[1];
                    $this->getRepository(Screen::class)->addData($screenSelectedId, $fieldSelectedId, null, $currentDate);
                }
            }

            // make field visible in all the field configurations

            $fieldConfigurations = $this->getRepository(FieldConfiguration::class)->getByClientId($session->get('client/id'));
            while ($fieldConfiguration = $fieldConfigurations->fetch_array(MYSQLI_ASSOC)) {
                $this->getRepository(FieldConfiguration::class)->addCompleteData($fieldConfiguration['id'], $fieldId, 1, 0, '');
            }

            $this->getLogger()->addInfo('UPDATE Yongo Custom Field ' . $field['name'], $this->getLoggerContext());

            if ($field['sys_field_type_id'] == Field::CUSTOM_FIELD_TYPE_SELECT_LIST_SINGLE_CODE_ID) {
                return new RedirectResponse('/yongo/administration/custom-fields/define/' . $fieldId);
            } else {
                return new RedirectResponse('/yongo/administration/custom-fields');
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Custome Field';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/custom/EditScreen.php', get_defined_vars());
    }
}