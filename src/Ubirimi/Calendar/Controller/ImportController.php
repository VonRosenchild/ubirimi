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

use Sabre\VObject;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Calendar\Repository\Calendar\UbirimiCalendar;
use Ubirimi\Calendar\Repository\Event\CalendarEvent;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ImportController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);

        $data = null;
        $filename = null;
        if ($request->request->has('import_calendar')) {
            if ($request->files->has('calendar_file')) {
                $temporaryFileName = $_FILES['calendar_file']['tmp_name'];
                $calendarName = 'Imported Calendar';
                $data = file_get_contents($temporaryFileName);
            }
        }

        if ($request->request->has('import_calendar_url')) {
            $url = $request->request->get('calendar_url');
            $data = file_get_contents($url);
        }

        if ($data) {
            $calendar = VObject\Reader::read($data);
            $calendarChildren = $calendar->children();
            foreach ($calendarChildren as $child) {
                if ('X-WR-CALNAME' == $child->name) {
                    $calendarName = $child->getValue();
                    break;
                }
            }
            $calendarExists = $this->getRepository(UbirimiCalendar::class)->getByName($session->get('user/id'), $calendarName);
            if ($calendarExists) {
                $calendarName .= '_' . time();
            }

            // deal with the events
            $date = Util::getServerCurrentDateTime();
            $calendarId = $this->getRepository(UbirimiCalendar::class)->save($session->get('user/id'), $calendarName, null, '#A1FF9E', $date);

            $defaultColor = 'A1FF9E';
            $events = $calendar->VEvent;
            for ($i = 0; $i < count($events); $i++) {
                $summary = $events[$i]->SUMMARY->getValue();
                $description = isset($events[$i]->DESCRIPTION) ? $events[$i]->DESCRIPTION->getValue() : null;
                $location = $events[$i]->LOCATION->getValue();
                $startTime = $events[$i]->DTSTART->getDateTime();
                $startTime = $startTime->format('Y-m-d H:i:s');

                $endTime = $events[$i]->DTEND->getDateTime();
                if ($endTime->format('H:i:s') == '00:00:00') {
                    $endTime = $endTime->sub(new \DateInterval('P1D'));
                }

                $endTime = $endTime->format('Y-m-d H:i:s');

                $this->getRepository(CalendarEvent::class)->add(
                    $calendarId,
                    $session->get('user/id'),
                    $summary,
                    $description,
                    $location,
                    $startTime,
                    $endTime,
                    $defaultColor,
                    $date
                );
            }
            if ($filename) {
                unlink('./temp_imported_calendar/' . $filename);
            }

            return new RedirectResponse('/calendar/calendars');
        }

        $menuSelectedCategory = 'calendars';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / Import';

        return $this->render(__DIR__ . '/../Resources/views/Import.php', get_defined_vars());
    }
}
