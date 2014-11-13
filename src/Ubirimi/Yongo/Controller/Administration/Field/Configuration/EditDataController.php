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
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\FieldConfiguration;

class EditDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $fieldConfigurationId = $request->get('field_configuration_id');
        $fieldId = $request->get('field_id');
        $visibleFlag = $request->get('visible_flag');
        $requiredFlag = $request->get('required_flag');

        $fieldConfiguration = $this->getRepository(FieldConfiguration::class)->getMetaDataById($fieldConfigurationId);
        $data = $this->getRepository(FieldConfiguration::class)->getDataByConfigurationAndField($fieldConfigurationId, $fieldId);
        if (!$data)
            $this->getRepository(FieldConfiguration::class)->addSimpleData($fieldConfigurationId, $fieldId);

        $this->getRepository(FieldConfiguration::class)->updateData($fieldConfigurationId, $fieldId, $visibleFlag, $requiredFlag);

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository(UbirimiLog::class)->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'UPDATE Yongo Field Configuration ' . $fieldConfiguration['name'],
            $currentDate
        );

        return new RedirectResponse('/yongo/administration/field-configuration/edit/' . $fieldConfigurationId);
    }
}
