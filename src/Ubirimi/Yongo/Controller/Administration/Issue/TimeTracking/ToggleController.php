<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\TimeTracking;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\SystemProduct;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Log;

class ToggleController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        Client::toggleTimeTrackingFeature($session->get('client/id'));

        $session->set('yongo/settings/time_tracking_flag', 1 - $session->get('yongo/settings/time_tracking_flag'));
        $logText = 'Activate';

        if (0 == $session->get('yongo/settings/time_tracking_flag')) {
            $logText = 'Deactivate';
        }

        $currentDate = Util::getServerCurrentDateTime();

        Log::add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            $logText . ' Yongo Time Tracking',
            $currentDate
        );

        return new RedirectResponse('/yongo/administration/issue-features/time-tracking');
    }
}
