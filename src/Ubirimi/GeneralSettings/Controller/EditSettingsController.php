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

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditSettingsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {

        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $session->set('selected_product_id', -1);
        $menuSelectedCategory = 'general_overview';

        $timezoneData = explode("/", $session->get('client/settings/timezone'));
        $timezoneContinent = $timezoneData[0];
        $timeZoneContinents = array('Africa' => 1, 'America' => 2, 'Antarctica' => 4, 'Arctic' => 8, 'Asia' => 16,
            'Atlantic' => 32, 'Australia' => 64, 'Europe' => 128, 'Indian' => 256, 'Pacific' => 512);
        $timeZoneCountry = $timezoneData[1];

        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
        $client = $this->getRepository(UbirimiClient::class)->getById($clientId);

        if ($request->request->has('update_configuration')) {

            $language = Util::cleanRegularInputField($request->request->get('language'));
            $timezone = Util::cleanRegularInputField($request->request->get('zone'));
            $titleName = Util::cleanRegularInputField($request->request->get('title_name'));
            $operatingMode = Util::cleanRegularInputField($request->request->get('mode'));

            $parameters = array(array('field' => 'title_name', 'value' => $titleName, 'type' => 's'),
                array('field' => 'operating_mode', 'value' => $operatingMode, 'type' => 's'),
                array('field' => 'language', 'value' => $language, 'type' => 's'),
                array('field' => 'timezone', 'value' => $timezone, 'type' => 's'));

            $this->getRepository(UbirimiClient::class)->updateProductSettings($clientId, 'client_settings', $parameters);

            $session->set('client/settings/language', $language);
            $session->set('client/settings/timezone', $timezone);

            return new RedirectResponse('/general-settings/view-general');
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / GeneralSettings Settings / Update';

        return $this->render(__DIR__ . '/../Resources/views/EditSettings.php', get_defined_vars());
    }
}