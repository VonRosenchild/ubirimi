<?php
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $projectId = $_GET['project_id'];

    $project = Project::getById($projectId);

    $menuSelectedCategory = 'help_desk';
    $menuProjectCategory = 'sla';
    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Help Desks';

    $emptyName = false;
    $duplicateName = false;

    $SLAs = SLA::getByProjectId($projectId);
    if ($SLAs) {
        $slaSelected = $SLAs->fetch_array(MYSQLI_ASSOC);
    }

    $availableStatuses = IssueSettings::getAllIssueSettings('status', $clientId);
    if (isset($_POST['confirm_new_sla'])) {

        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        $slaExists = SLA::getByName(mb_strtolower($name), $projectId);
        if ($slaExists)
            $duplicateName = true;

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

            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

            $slaId = SLA::save($projectId, $name, $description, $startCondition, $stopCondition, $currentDate);

            // add the goals of the sla
            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 16) == 'goal_definition_') {
                    $index = str_replace('goal_definition_', '', $key);
                    if ($value && $_POST['goal_value_' . $index]) {

                        SLA::addGoal($slaId, $value, $value, $_POST['goal_value_' . $index]);
                    }
                }
            }

            // for every issue in this project add an empty line in yongo_issue_sla
            $issuesData = Issue::getByParameters(array('project' => $projectId));
            if ($issuesData->num_rows) {
                while ($issue = $issuesData->fetch_array(MYSQLI_ASSOC)) {
                    Issue::addPlainSLAData($issue['id'], $projectId);
                }
            }

            header('Location: /helpdesk/sla/' . $projectId . '/' . $slaId);
        }
    }

    require_once __DIR__ . '/../../Resources/views/sla/Add.php';