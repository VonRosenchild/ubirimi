<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\ProjectCategory;

    Util::checkUserIsLoggedInAndRedirect();

    $categoryId = $_GET['id'];
    $projectId = $session->get('selected_project_id');
    $category = ProjectCategory::getById($categoryId);

    if ($category['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $alreadyExists = false;

    if (isset($_POST['edit_release'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $dateUpdated = Util::getServerCurrentDateTime();
            ProjectCategory::updateById($categoryId, $name, $description, $dateUpdated);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Project Category ' . $name, $dateUpdated);

            header('Location: /yongo/administration/project/categories');
        }
    }
    $menuSelectedCategory = 'project';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Project Category';

    require_once __DIR__ . '/../../../../Resources/views/administration/project/category/Edit.php';