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

namespace Ubirimi\Calendar\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $calendarId = $request->get('id');
        $calendar = $this->getRepository(UbirimiCalendar::class)->getById($calendarId);

        if ($calendar['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $calendarExists = false;

        if ($request->request->has('edit_calendar')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $color = Util::cleanRegularInputField($request->request->get('color'));

            if (empty($name)) {
                $emptyName = true;
            }

            // check for duplication
            $calendarDuplicate = $this->getRepository(UbirimiCalendar::class)->getByName($session->get('user/id'), mb_strtolower($name), $calendarId);
            if ($calendarDuplicate) {
                $calendarExists = true;
            }
            if (!$calendarExists && !$emptyName) {
                $date = Util::getServerCurrentDateTime();
                $this->getRepository(UbirimiCalendar::class)->updateById($calendarId, $name, $description, $color, $date);

                $this->getLogger()->addInfo('UPDATE EVENTS calendar ' . $name, $this->getLoggerContext());

                return new RedirectResponse('/calendar/calendars');
            }
        }

        $menuSelectedCategory = 'calendar';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / Calendar: ' . $calendar['name'] . ' / Update';

        return $this->render(__DIR__ . '/../Resources/views/Edit.php', get_defined_vars());
    }
}