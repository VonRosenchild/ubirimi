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

    $resolution = IssueSettings::getById($oldId, 'resolution');
    $projects = Client::getProjects($clientId, 'array', 'id');
    Issue::updateResolution($projects, $oldId, $newId);

    IssueSettings::deleteResolutionById($oldId);

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Issue Resolution ' . $resolution['name'], $currentDate);