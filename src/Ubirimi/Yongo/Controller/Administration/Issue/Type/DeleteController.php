<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueType;

    Util::checkUserIsLoggedInAndRedirect();

    $oldId = $_POST['id'];
    $newId = isset($_POST['new_id']) ? $_POST['new_id'] : null;

    if ($newId) {
        $projects = Client::getProjects($clientId, 'array', 'id');
        Issue::updateType($projects, $oldId, $newId);
    }

    $issueType = IssueType::getById($oldId);
    IssueType::deleteById($oldId);

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Issue Type ' . $issueType['name'], $currentDate);