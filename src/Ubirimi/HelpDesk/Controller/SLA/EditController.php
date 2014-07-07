<?php
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;
    use Ubirimi\Yongo\Repository\Project\Project;
    use Ubirimi\Repository\HelpDesk\SLACalendar;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $slaId = $_GET['id'];

    $SLA = SLA::getById($slaId);
    $project = Project::getById($SLA['project_id']);

    $startConditions = explode("#", $SLA['start_condition']);
    $stopConditions = explode("#", $SLA['stop_condition']);

    $slaConditions = array_merge($startConditions, $stopConditions);
    $slaCalendars = SLACalendar::getByProjectId($SLA['project_id']);
    $goals = SLA::getGoals($slaId);
    $menuSelectedCategory = 'help_desk';
    $menuProjectCategory = 'sla';

    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Help Desks';

    $emptyName = false;
    $duplicateName = false;

    $availableStatuses = IssueSettings::getAllIssueSettings('status', $clientId);

    if (isset($_POST['confirm_update_sla'])) {

        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name)) {
            $emptyName = true;
        }

        $slaExists = SLA::getByName(mb_strtolower($name), $SLA['project_id'], $slaId);
        if ($slaExists) {
            $duplicateName = true;
        }

        // get start conditions
        $startCondition = '';
        $keys = array();
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 6) == 'start_') {
                $keys[] = $key;
            }
        }
        $startCondition = implode('#', $keys);

        // get end conditions
        $stopCondition = '';
        $keys = array();
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 5) == 'stop_') {
                $keys[] = $key;
            }
        }
        $stopCondition = implode('#', $keys);

        if (!$emptyName && !$duplicateName) {

            $currentDate = Util::getServerCurrentDateTime();

            SLA::updateById($slaId, $name, $description, $startCondition, $stopCondition, $currentDate);

            SLA::deleteGoalsBySLAId($slaId);
            // add the goals of the sla
            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 16) == 'goal_definition_') {
                    $index = str_replace('goal_definition_', '', $key);
                    if ($value && $_POST['goal_value_' . $index]) {
                        SLA::addGoal($slaId, $_POST['goal_calendar_' . $index], $value, $value, $_POST['goal_value_' . $index]);
                    }
                }
            }

            // clear all this SLA information for all issues in this project
            Issue::clearSLAData($slaId);
            header('Location: /helpdesk/sla/' . $SLA['project_id'] . '/' . $slaId);
        }
    }

    $allRemainingIssuesDefinitionFound = false;

    require_once __DIR__ . '/../../Resources/views/sla/Edit.php';