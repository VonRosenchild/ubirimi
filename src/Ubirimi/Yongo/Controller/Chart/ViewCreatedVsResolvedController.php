<?php

namespace Ubirimi\Yongo\Controller\Chart;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\Project;

class ViewCreatedVsResolvedController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $clientId = $session->get('client/id');

        $helpdeskFlag = 0;
        $loggedInUser = Util::checkUserIsLoggedIn();
        if ($loggedInUser) {
            if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_HELP_DESK) {
                $helpdeskFlag = 1;
            }
        }

        $projectId = $request->request->get('id');

        $date = date("Y-m-d", strtotime("-1 month"));
        $date = date('Y-m-d', strtotime("+1 day", strtotime($date)));

        $dateWithoutYear = date("d M", strtotime("-1 month"));
        $currentDate = date("Y-m-d");

        $result = array();
        while (strtotime($date) <= strtotime($currentDate)) {
            $countAllIssues = Project::getTotalIssuesPreviousDate($clientId, $projectId, $date, $helpdeskFlag);
            $resolvedCount = Project::getTotalIssuesResolvedOnDate($clientId, $projectId, $date, $helpdeskFlag);

            $result[] = array($dateWithoutYear, $countAllIssues, $resolvedCount);
            $dateWithoutYear = date('d M', strtotime("+1 day", strtotime($date)));
            $date = date('Y-m-d', strtotime("+1 day", strtotime($date)));
        }

        return new JsonResponse($result);
    }
}