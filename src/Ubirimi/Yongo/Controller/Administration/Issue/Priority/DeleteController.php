<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;

    Util::checkUserIsLoggedInAndRedirect();

    $oldId = $_POST['id'];
    $newId = $_POST['new_id'];

    $priority = IssueSettings::getById($oldId, 'priority');
    $projects = Client::getProjects($clientId, 'array', 'id');
    Issue::updatePriority($projects, $oldId, $newId);

    IssueSettings::deletePriorityById($oldId);

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Issue Priority ' . $priority['name'], $currentDate);