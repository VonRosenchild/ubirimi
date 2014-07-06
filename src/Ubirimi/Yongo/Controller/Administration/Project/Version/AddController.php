<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];
    $project = Project::getById($projectId);

    $emptyName = false;
    $alreadyExists = false;

    if (isset($_POST['confirm_new_release'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        $releasesDuplicate = Project::getVersionByName($projectId, $name);
        if ($releasesDuplicate)
            $alreadyExists = true;

        if (!$emptyName && !$alreadyExists) {
            $currentDate = Util::getServerCurrentDateTime();
            Project::addVersion($projectId, $name, $description, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Project Version ' . $name, $currentDate);

            header('Location: /yongo/administration/project/versions/' . $projectId);
        }
    }

    $menuSelectedCategory = 'project';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Project Version';

    require_once __DIR__ . '/../../../../Resources/views/administration/project/version/Add.php';