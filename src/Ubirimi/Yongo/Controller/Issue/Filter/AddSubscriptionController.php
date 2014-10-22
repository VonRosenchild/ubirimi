<?php

namespace Ubirimi\Yongo\Controller\Issue\Filter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddSubscriptionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $minute = $request->request->get('minute');
        $hour = $request->request->get('hour');
        $day = $request->request->get('day');
        $month = $request->request->get('month');
        $weekday = $request->request->get('weekday');

        $filterId = $request->request->get('id');
        $recipientId = $request->request->get('recipient_id');
        $emailWhenEmptyFlag = $request->request->get('email_when_empty');

        $userId = null;
        $groupId = null;
        if (substr($recipientId, 0, 1) == 'u') {
            $userId = substr($recipientId, 1);
        } else {
            $groupId = substr($recipientId, 1);
        }
        $cronExpression = implode(',', $minute) . ' ' . implode(',', $hour) . ' ' . implode(',', $day) . ' ' . implode(',', $month) . ' ' . implode(',', $weekday);

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository('yongo.issue.filter')->addSubscription($filterId, $loggedInUserId, $userId, $groupId, $cronExpression, $emailWhenEmptyFlag, $currentDate);

        return new Response('');
    }
}