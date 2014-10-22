<?php

namespace Ubirimi\HelpDesk\Controller\SLA;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Sla\Calendar;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $slaId = $request->get('id');

        $SLA = $this->getRepository('helpDesk.sla.sla')->getById($slaId);
        $project = $this->getRepository('yongo.project.project')->getById($SLA['project_id']);

        $startConditions = explode("#", $SLA['start_condition']);
        $stopConditions = explode("#", $SLA['stop_condition']);

        $slaConditions = array_merge($startConditions, $stopConditions);
        $slaCalendars = Calendar::getByProjectId($SLA['project_id']);
        $goals = $this->getRepository('helpDesk.sla.sla')->getGoals($slaId);
        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'sla';

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Help Desks';

        $emptyName = false;
        $duplicateName = false;

        $availableStatuses = $this->getRepository('yongo.issue.settings')->getAllIssueSettings('status', $session->get('client/id'));

        if ($request->request->has('confirm_update_sla')) {

            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $slaExists = $this->getRepository('helpDesk.sla.sla')->getByName(mb_strtolower($name), $SLA['project_id'], $slaId);
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

                $this->getRepository('helpDesk.sla.sla')->updateById($slaId, $name, $description, $startCondition, $stopCondition, $currentDate);

                $this->getRepository('helpDesk.sla.sla')->deleteGoalsBySLAId($slaId);
                // add the goals of the sla
                foreach ($request->request as $key => $value) {
                    if (substr($key, 0, 16) == 'goal_definition_') {
                        $index = str_replace('goal_definition_', '', $key);
                        if ($value && $request->request->get('goal_value_' . $index)) {
                            $this->getRepository('helpDesk.sla.sla')->addGoal(
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
                $this->getRepository('yongo.issue.issue')->clearSLAData($slaId);

                return new RedirectResponse('/helpdesk/sla/' . $SLA['project_id'] . '/' . $slaId);
            }
        }

        $allRemainingIssuesDefinitionFound = false;

        return $this->render(__DIR__ . '/../../Resources/views/sla/Edit.php', get_defined_vars());
    }
}
