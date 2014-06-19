<?php
    use Ubirimi\Repository\HelpDesk\Queue;
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $projectId = $_GET['id'];
    $slaSelectedId = $_GET['sla_id'];

    $project = Project::getById($projectId);
    $SLAs = SLA::getByProjectId($projectId);

    $menuSelectedCategory = 'help_desk';
    $menuProjectCategory = 'sla';
    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Help Desks';

    $queues = Queue::getByProjectId($projectId);
    if ($queues) {
        $queueSelected = $queues->fetch_array(MYSQLI_ASSOC);
    }
    $slaSelected = SLA::getById($slaSelectedId);

    $startConditions = explode("#", $slaSelected['start_condition']);
    $stopConditions = explode("#", $slaSelected['stop_condition']);

    $goals = SLA::getGoals($slaSelectedId);
    $allRemainingIssuesDefinitionFound = false;

    require_once __DIR__ . '/../../Resources/views/sla/View.php';