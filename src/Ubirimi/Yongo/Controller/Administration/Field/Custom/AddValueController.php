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

class AddValueController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $emptyValue = false;
        $duplicateValue = false;

        $customFieldId = $request->get('id');
        $field = $this->getRepository(Field::class)->getById($customFieldId);

        if ($request->request->has('new_custom_value')) {
            $value = Util::cleanRegularInputField($request->request->get('value'));
            if (empty($value))
                $emptyValue = true;

            if (!$emptyValue) {
                $customValueExists = $this->getRepository(Field::class)->getDataByFieldIdAndValue($customFieldId, $value);
                if ($customValueExists)
                    $duplicateValue = true;
            }

            if (!$emptyValue && !$duplicateValue) {
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository(Field::class)->addData($customFieldId, $value, $currentDate);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Field Custom Value ' . $value,
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/custom-fields/define/' . $customFieldId);
            }
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Add Custom Field Value';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/custom/AddValue.php', get_defined_vars());
    }
}