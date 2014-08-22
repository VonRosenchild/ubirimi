<?php
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\Repository\HelpDesk\SLACalendar;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $menuSelectedCategory = 'help_desk';
    $menuProjectCategory = 'sla_calendar';
    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / SLA Calendars';

    $clientId = $session->get('client/id');
    $projectId = $_GET['id'];
    $project = Project::getById($projectId);
    $calendars = SLACalendar::getByProjectId($projectId);

    $SLAs = SLA::getByProjectId($projectId);
    if ($SLAs) {
        $slaSelected = $SLAs->fetch_array(MYSQLI_ASSOC);
        $SLAs->data_seek(0);
    }

    require_once __DIR__ . '/../../../Resources/views/sla/calendar/List.php';