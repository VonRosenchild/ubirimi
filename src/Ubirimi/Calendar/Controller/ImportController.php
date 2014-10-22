<?php

namespace Ubirimi\Calendar\Controller;

use Sabre\VObject;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\Calendar;
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
            $calendarExists = $this->getRepository('calendar.calendar.calendar')->getByName($session->get('user/id'), $calendarName);
            if ($calendarExists) {
                $calendarName .= '_' . time();
            }

            // deal with the events
            $date = Util::getServerCurrentDateTime();
            $calendarId = $this->getRepository('calendar.calendar.calendar')->save($session->get('user/id'), $calendarName, null, '#A1FF9E', $date);

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

                $this->getRepository('calendar.event.event')->add(
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
