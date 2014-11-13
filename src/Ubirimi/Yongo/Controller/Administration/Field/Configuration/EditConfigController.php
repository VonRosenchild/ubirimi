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

namespace Ubirimi\Yongo\Controller\Administration\Field\Configuration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Field\FieldConfiguration;

class EditConfigController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $fieldConfigurationId = $request->get('field_configuration_id');
        $fieldId = $request->get('id');

        $fieldConfiguration = $this->getRepository(FieldConfiguration::class)->getMetaDataById($fieldConfigurationId);

        if ($fieldConfiguration['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $fieldConfigurationData = $this->getRepository(FieldConfiguration::class)->getDataByConfigurationAndField($fieldConfigurationId, $fieldId);
        $description = $fieldConfigurationData['field_description'];
        $field = $this->getRepository(Field::class)->getById($fieldId);

        if ($field['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $menuSelectedCategory = 'issue';

        if ($request->request->has('edit_field_configuration')) {
            $description = $request->request->get('description');
            $this->getRepository(FieldConfiguration::class)->updateFieldDescription($fieldConfigurationId, $fieldId, $description);

            return new RedirectResponse('/yongo/administration/field-configuration/edit/' . $fieldConfigurationId);
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Field';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/configuration/EditConfig.php', get_defined_vars());
    }
}
