<?php

namespace Ubirimi\HelpDesk\Controller\Report;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\HelpDesk\SLA;

class GetDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $slaId = $request->get('id');

        $dateTo = date('Y-m-d');
        $dateFrom = new \DateTime($dateTo, new \DateTimeZone($clientSettings['timezone']));
        $dateFrom = date_sub($dateFrom, date_interval_create_from_date_string('60 days'));

        $issues = SLA::getIssues($slaId, $dateFrom->format('Y-m-d'), $dateTo);

        $dates = array();
        $succeeded = array();
        $breached = array();

        while (date_format($dateFrom, 'Y-m-d') <= $dateTo) {
            $dates[] = date_format($dateFrom, 'Y-m-d');
            $succeeded[end($dates)] = 0;
            $breached[end($dates)] = 0;
            date_add($dateFrom, date_interval_create_from_date_string('1 days'));
        }

        while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {

            if ($issue['stopped_date'] == null && end($dates) == date('Y-m-d'))
            if ($issue['sla_value'] >= 0) {
                $succeeded[substr($issue['stopped_date'], 0, 10)]++;
            } else {
                $breached[substr($issue['stopped_date'], 0, 10)]++;
            }
        }

        $data = array('dates' => $dates, 'succeeded' => $succeeded, 'breached' => $breached);

        return new JsonResponse($data);
    }
}