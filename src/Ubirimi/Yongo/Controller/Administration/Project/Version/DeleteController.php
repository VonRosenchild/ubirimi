<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $releaseId = $_POST['release_id'];
    $release = Project::getVersionById($releaseId);

    Project::deleteVersionById($releaseId);

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Project Version ' . $release['name'], $currentDate);