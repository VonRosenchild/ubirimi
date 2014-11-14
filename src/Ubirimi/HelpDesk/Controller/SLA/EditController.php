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

namespace Ubirimi\HelpDesk\Controller\SLA;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\HelpDesk\Repository\Sla\SlaCalendar;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $slaId = $request->get('id');

        $SLA = $this->getRepository(Sla::class)->getById($slaId);
        $project = $this->getRepository(YongoProject::class)->getById($SLA['project_id']);

        $startConditions = explode("#", $SLA['start_condition']);
        $stopConditions = explode("#", $SLA['stop_condition']);

        $slaConditions = array_merge($startConditions, $stopConditions);
        $slaCalendars = $this->getRepository(SlaCalendar::class)->getByProjectId($SLA['project_id']);
        $goals = $this->getRepository(Sla::class)->getGoals($slaId);
        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'sla';

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Help Desks';

        $emptyName = false;
        $duplicateName = false;

        $availableStatuses = $this->getRepository(IssueSettings::class)->getAllIssueSettings('status', $session->get('client/id'));

        if ($request->request->has('confirm_update_sla')) {

            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $slaExists = $this->getRepository(Sla::class)->getByName(mb_strtolower($name), $SLA['project_id'], $slaId);
            if ($slaExists) {
                $duplicateName = true;
            }

            // get start conditions
            $startCondition = '';
            $keys = array();
            foreach ($request->request as $key => $value) {
                if (substr($key, 0, 6) == 'start_') {
                    $keys[] = $key;
                }
            }
            $startCondition = implode('#', $keys);

            // get end conditions
            $stopCondition = '';
            $keys = array();
            foreach ($request->request as $key => $value) {
                if (substr($key, 0, 5) == 'stop_') {
                    $keys[] = $key;
                }
            }
            $stopCondition = implode('#', $keys);

            if (!$emptyName && !$duplicateName) {

                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository(Sla::class)->updateById($slaId, $name, $description, $startCondition, $stopCondition, $currentDate);

                $this->getRepository(Sla::class)->deleteGoalsBySLAId($slaId);
                // add the goals of the sla
                foreach ($request->request as $key => $value) {
                    if (substr($key, 0, 16) == 'goal_definition_') {
                        $index = str_replace('goal_definition_', '', $key);
                        if ($value && $request->request->get('goal_value_' . $index)) {
                            $this->getRepository(Sla::class)->addGoal(
                                $slaId,
                                $request->request->get('goal_calendar_' . $index),
                                $value,
                                $value,
                                $request->request->get('goal_value_' . $index)
                            );
                        }
                    }
                }

                // clear all this SLA information for all issues in this project
                $this->getRepository(Issue::class)->clearSLAData($slaId);

                return new RedirectResponse('/helpdesk/sla/' . $SLA['project_id'] . '/' . $slaId);
            }
        }

        $allRemainingIssuesDefinitionFound = false;

        return $this->render(__DIR__ . '/../../Resources/views/sla/Edit.php', get_defined_vars());
    }
}
