<?php
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $slaId = $_POST['id'];
    $projectId = $_POST['project_id'];

    SLA::deleteById($slaId);

    $SLAs = SLA::getByProjectId($projectId);
    $slaToGoId = -1;
    if ($SLAs) {
        $firstSLA = $SLAs->fetch_array(MYSQLI_ASSOC);
        $slaToGoId = $firstSLA['id'];
    }

    echo '/helpdesk/sla/' . $projectId . '/' . $slaToGoId;