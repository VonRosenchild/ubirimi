<?php

namespace Ubirimi\Yongo\Controller\Issue\Filter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;

class AddSubscriptionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $minute = $request->request->get('minute');
        $hour = $request->request->get('hour');
        $day = $request->request->get('day');
        $month = $request->request->get('month');
        $weekday = $request->request->get('weekday');

        $filterId = $request->request->get('id');
        $recipientId = $request->request->get('recipient_id');

        $userId = null;
        $groupId = null;
        if (substr($recipientId, 0, 1) == 'u') {
            $userId = substr($recipientId, 1);
        } else {
            $groupId = substr($recipientId, 1);
        }
        $cronExpression = implode(',', $minute) . ' ' . implode(',', $hour) . ' ' . implode(',', $day) . ' ' . implode(',', $month) . ' ' . implode(',', $weekday);

        $currentDate = Util::getServerCurrentDateTime();

        IssueFilter::addSubscription($filterId, $userId, $groupId, $cronExpression, 0, $currentDate);

        return new Response($cronExpression);
    }
}