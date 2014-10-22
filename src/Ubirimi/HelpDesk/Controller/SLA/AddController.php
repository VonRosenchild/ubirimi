<?php

namespace Ubirimi\HelpDesk\Controller\SLA;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Sla\Calendar;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientSettings = $session->get('client/settings');

        $projectId = $request->get('project_id');

        $project = $this->getRepository('yongo.project.project')->getById($projectId);

        $menuSelectedCategory = 'help_desk';
        $menuProjectCategory = 'sla';
        $sectionPageTitle = $clientSettings['title_name']
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Help Desks';

        $emptyName = false;
        $duplicateName = false;

        $SLAs = $this->getRepository('helpDesk.sla.sla')->getByProjectId($projectId);
        if ($SLAs) {
            $slaSelected = $SLAs->fetch_array(MYSQLI_ASSOC);
        }
        $slaCalendars = Calendar::getByProjectId($projectId);

        $availableStatuses = $this->getRepository('yongo.issue.settings')->getAllIssueSettings('status', $session->get('client/id'));

        if ($request->request->has('confirm_new_sla')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name)) {
                $emptyName = true;
            }

            $slaExists = $this->getRepository('helpDesk.sla.sla')->getByName(mb_strtolower($name), $projectId);
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

                $slaId = $this->getRepository('helpDesk.sla.sla')->save(
                    $projectId,
                    $name,
                    $description,
                    $startCondition,
                    $stopCondition,
                    $currentDate
                );

                // add the goals of the sla
                foreach ($request->request as $key => $value) {
                    if (substr($key, 0, 16) == 'goal_definition_') {
                        $index = str_replace('goal_definition_', '', $key);

                        if ($request->request->has('goal_value_' . $index)) {
                            if ($value == '') {
                                $value = 'all_remaining_issues';
                            }

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

                // for every issue in this project add an empty line in yongo_issue_sla
                $issuesData = $this->getRepository('yongo.issue.issue')->getByParameters(array('project' => $projectId));
                if ($issuesData->num_rows) {
                    while ($issue = $issuesData->fetch_array(MYSQLI_ASSOC)) {
                        $this->getRepository('yongo.issue.issue')->addPlainSLADataBySLAId($issue['id'], $slaId);
                    }
                }

                return new RedirectResponse('/helpdesk/sla/' . $projectId . '/' . $slaId);
            }
        }

        return $this->render(__DIR__ . '/../../Resources/views/sla/Add.php', get_defined_vars());
    }
}
