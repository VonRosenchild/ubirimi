<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Yongo\Controller\Administration\Issue\TimeTracking;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewController extends UbirimiController
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

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Time Tracking Settings';

        return $this->render(__DIR__ . '/../../../../Resources/views/administration/issue/time_tracking/View.php', get_defined_vars());
    }
}
