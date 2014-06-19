<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\ProjectCategory;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;
    $duplicateName = false;

    if (isset($_POST['add_project_category'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;
        else {
            $data = ProjectCategory::getByName($name, null, $clientId);
            if ($data)
                $duplicateName = true;
        }

        if (!$emptyName && !$duplicateName) {
            $projectCategory = new ProjectCategory($clientId, $name, $description);
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $projectCategory->save($currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Project Category ' . $name, $currentDate);

            header('Location: /yongo/administration/project/categories');
        }
    }
    $menuSelectedCategory = 'project';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Project Category';

    require_once __DIR__ . '/../../../../Resources/views/administration/project/category/Add.php';