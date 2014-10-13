<?php

namespace Ubirimi\HelpDesk\Controller\SLA;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\HelpDesk\Sla;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Settings;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Repository\HelpDesk\SLACalendar;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $slaId = $request->get('id');

        $SLA = Sla::getById($slaId);
        $project = $this->getRepository('yongo.project.project')->getById($SLA['project_id']);

        $startConditions = explode("#", $SLA['start_condition']);
        $stopConditions = explode("#", $SLA['stop_condition']);

        $slaConditions = array_merge($startConditions, $stopConditions);
        $slaCalendars = SLACalendar::getByProjectId($SLA['project_id']);
        $goals = Sla::getGoals($slaId);
        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'sla';

        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Help Desks';

        $emptyName = false;
        $duplicateName = false;

        $availableStatuses = Settings::getAllIssueSettings('status', $session->get('client/id'));

        if ($request->request->has('confirm_update_sla')) {

            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $slaExists = Sla::getByName(mb_strtolower($name), $SLA['project_id'], $slaId);
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

                Sla::updateById($slaId, $name, $description, $startCondition, $stopCondition, $currentDate);

                Sla::deleteGoalsBySLAId($slaId);
                // add the goals of the sla
                foreach ($request->request as $key => $value) {
                    if (substr($key, 0, 16) == 'goal_definition_') {
                        $index = str_replace('goal_definition_', '', $key);
                        if ($value && $request->request->get('goal_value_' . $index)) {
                            Sla::addGoal(
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
                Issue::clearSLAData($slaId);

                return new RedirectResponse('/helpdesk/sla/' . $SLA['project_id'] . '/' . $slaId);
            }
        }

        $allRemainingIssuesDefinitionFound = false;

        return $this->render(__DIR__ . '/../../Resources/views/sla/Edit.php', get_defined_vars());
    }
}
