<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_POST['project_id'];
    $projectDeleted = Project::getById($projectId);

    Project::deleteById($projectId);

    if ($projectId == $session->get('selected_project_id')) {
        $session->set('selected_project_id', null);
    }

    $currentDate = Util::getServerCurrentDateTime();

    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Project ' . $projectDeleted['name'], $currentDate);