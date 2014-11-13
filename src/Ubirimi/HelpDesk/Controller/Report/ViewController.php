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

namespace Ubirimi\HelpDesk\Controller\Report;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Queue\Queue;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $projectId = $request->get('id');
        $slaSelectedId = $request->get('sla_id');

        $project = $this->getRepository(YongoProject::class)->getById($projectId);
        $SLAs = $this->getRepository(Sla::class)->getByProjectId($projectId);

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'reports';

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Help Desks';

        $queues = $this->getRepository(Queue::class)->getByProjectId($projectId);

        if ($queues) {
            $queueSelected = $queues->fetch_array(MYSQLI_ASSOC);
        }

        $slaSelected = $this->getRepository(Sla::class)->getById($slaSelectedId);

        $dateTo = date('Y-m-d');
        $dateFrom = new \DateTime($dateTo, new \DateTimeZone($clientSettings['timezone']));
        $dateFrom = date_sub($dateFrom, date_interval_create_from_date_string('1 months'))->format('Y-m-d');

        return $this->render(__DIR__ . '/../../Resources/views/report/View.php', get_defined_vars());
    }
}
