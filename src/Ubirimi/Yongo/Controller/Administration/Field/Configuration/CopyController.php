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


class CopyController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $fieldConfigurationId = $request->get('id');
        $fieldConfiguration = $this->getRepository(FieldConfiguration::class)->getMetaDataById($fieldConfigurationId);

        if ($fieldConfiguration['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $duplicateName = false;

        if ($request->request->has('copy_field_configuration')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $duplicateFieldConfiguration = $this->getRepository(FieldConfiguration::class)->getMetaDataByNameAndClientId(
                $session->get('client/id'),
                mb_strtolower($name)
            );

            if ($duplicateFieldConfiguration)
                $duplicateName = true;

            if (!$emptyName && !$duplicateName) {
                $copiedFieldConfiguration = new FieldConfiguration($session->get('client/id'), $name, $description);

                $currentDate = Util::getServerCurrentDateTime();
                $copiedFieldConfigurationId = $copiedFieldConfiguration->save($currentDate);

                $fieldConfigurationData = $this->getRepository(FieldConfiguration::class)->getDataByConfigurationId($fieldConfigurationId);

                while ($fieldConfigurationData && $data = $fieldConfigurationData->fetch_array(MYSQLI_ASSOC)) {
                    $copiedFieldConfiguration->addCompleteData(
                        $copiedFieldConfigurationId,
                        $data['field_id'],
                        $data['visible_flag'],
                        $data['required_flag'],
                        $data['field_description']
                    );
                }

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'Copy Yongo Field Configuration ' . $fieldConfiguration['name'],
                    $currentDate
                );

                return new RedirectResponse('/yongo/administration/field-configurations');
            }
        }

        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Copy Field Configuration';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/field/configuration/Copy.php', get_defined_vars());
    }
}
