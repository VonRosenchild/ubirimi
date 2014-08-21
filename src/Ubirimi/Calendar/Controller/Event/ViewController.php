<?php

namespace Ubirimi\Calendar\Controller\Event;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Calendar\Repository\CalendarEvent;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_CALENDAR);

        $eventId = $request->get('id');

        $event = CalendarEvent::getById($eventId, 'array');
        if ($event['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        // check if the event is a shared event
        $sharedEvent = CalendarEvent::getShareByUserIdAndEventId($session->get('user/id'), $event['id']);
        $guests = CalendarEvent::getGuests($eventId);

        $menuSelectedCategory = 'calendars';

        $month = date('n');
        $year = date('Y');

        $eventReminders = CalendarEvent::getReminders($eventId);
        $sourcePageLink = $request->get('source');

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / '
            . SystemProduct::SYS_PRODUCT_CALENDAR_NAME
            . ' / '
            . $event['name'];

        return $this->render(__DIR__ . '/../../Resources/views/event/View.php', get_defined_vars());
    }
}
