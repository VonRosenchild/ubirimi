<?php

namespace Ubirimi\General\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class RefreshTimezoneController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $zone = $request->request->get('zone');

        $zones = \DateTimeZone::listIdentifiers($zone);
        for ($i = 0; $i < count($zones); $i++) {
            $dateTimeZone0 = new \DateTimeZone($zones[$i]);
            $dateTime0 = new \DateTime("now", $dateTimeZone0);
            $timeOffset = $dateTimeZone0->getOffset($dateTime0);
            $prefix = ($timeOffset > 0) ? '+' : '';
            $data = explode("/", $zones[$i]);
            $prefixContinent = $data[0];
            $offset = (($timeOffset / 60 / 60));

            if ($offset < 0) {
                $offset++;
            } else {
                $offset--;
            }

            return new Response('<option value="' . $zones[$i] . '">' . str_replace(array($prefixContinent . "/", "_"), array("", " "), $zones[$i]) . ' (GMT ' . $prefix . $offset . 'h)</option>');
        }
    }
}