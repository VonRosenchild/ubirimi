<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\TimeTracking;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'system';
        $timeTrackingFlag = $session->get('yongo/settings/time_tracking_flag');
        $defaultTimeTracking = null;

        switch ($session->get('yongo/settings/time_tracking_default_unit')) {
            case 'w':
                $defaultTimeTracking = 'week';
                break;
            case 'd':
                $defaultTimeTracking = 'day';
                break;
            case 'h':
                $defaultTimeTracking = 'hours';
                break;
            case 'm':
                $defaultTimeTracking = 'minute';
                break;
        }

        if ($request->request->has('edit_time_tracking')) {
            $hoursPerDay = $request->request->get('hours_per_day');
            $daysPerWeek = $request->request->get('days_per_week');
            $defaultUnit = $request->request->get('default_unit');

            $this->getRepository('ubirimi.general.client')->updateTimeTrackingSettings(
                $session->get('client/id'),
                $hoursPerDay,
                $daysPerWeek,
                $defaultUnit
            );

            $currentDate = Util::getServerCurrentDateTime();

            $this->getRepository('ubirimi.general.log')->add(
                $session->get('client/id'),
                SystemProduct::SYS_PRODUCT_YONGO,
                $session->get('user/id'),
                'UPDATE Yongo Time Tracking Settings',
                $currentDate
            );

            $session->set('yongo/settings/time_tracking_hours_per_day', $hoursPerDay);
            $session->set('yongo/settings/time_tracking_days_per_week', $daysPerWeek);
            $session->set('yongo/settings/time_tracking_default_unit', $defaultUnit);

            return new RedirectResponse('/yongo/administration/issue-features/time-tracking');
        }

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/time_tracking/Edit.php', get_defined_vars());
    }
}
