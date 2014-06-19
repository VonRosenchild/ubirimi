<?php
    use Sabre\VObject;
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\Calendar\Repository\CalendarEvent;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);

    $data = null;
    $filename = null;
    if (isset($_POST['import_calendar'])) {
        if (isset($_FILES['calendar_file'])) {
            $temporaryFileName = $_FILES['calendar_file']['tmp_name'];
            $calendarName = 'Imported Calendar';
            $data = file_get_contents($temporaryFileName);
        }
    }

    if (isset($_POST['import_calendar_url'])) {
        $url = $_POST['calendar_url'];
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
        $calendarExists = Calendar::getByName($loggedInUserId, $calendarName);
        if ($calendarExists) {
            $calendarName .= '_' . time();
        }

        // deal with the events
        $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
        $calendarId = Calendar::save($loggedInUserId, $calendarName, null, '#A1FF9E', $date);

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
                $endTime = $endTime->sub(new DateInterval('P1D'));
            }

            $endTime = $endTime->format('Y-m-d H:i:s');

//            if ($summary == 'Send e-mail with evaluations from next month') {
//                var_dump($events[$i]->RRULE->getValue());
//                die();
//            }
            CalendarEvent::add($calendarId, $loggedInUserId, $summary, $description, $location, $startTime, $endTime, $defaultColor, $date);
        }
        if ($filename) {
            unlink('./temp_imported_calendar/' . $filename);
        }
        header('Location: /calendar/calendars');
    }

    $menuSelectedCategory = 'calendars';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CALENDAR_NAME . ' / Import';

    require_once __DIR__ . '/../Resources/views/Import.php';