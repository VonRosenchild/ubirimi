<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\ProjectCategory;

    Util::checkUserIsLoggedInAndRedirect();

    $projectCategoryId = $_POST['id'];
    $projectCategory = ProjectCategory::getById($projectCategoryId);
    ProjectCategory::deleteById($clientId, $projectCategoryId);

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Project Category ' . $projectCategory['name'], $currentDate);