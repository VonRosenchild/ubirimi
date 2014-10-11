<?php

namespace Ubirimi\HelpDesk\Controller\Report;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\HelpDesk\Sla;

class GetDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $slaId = $request->get('id');

        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $issues = Sla::getIssues($slaId, $dateFrom, $dateTo);

        $dates = array();
        $succeeded = array();
        $breached = array();


        $dateTemporary = new \DateTime($dateFrom, new \DateTimeZone($clientSettings['timezone']));
        while (date_format($dateTemporary, 'Y-m-d') <= $dateTo) {
            $dates[] = date_format($dateTemporary, 'Y-m-d');
            $succeeded[end($dates)] = 0;
            $breached[end($dates)] = 0;
            date_add($dateTemporary, date_interval_create_from_date_string('1 days'));
        }

        while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
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