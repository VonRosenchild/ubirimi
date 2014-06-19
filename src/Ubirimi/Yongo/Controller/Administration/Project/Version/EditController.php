<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $versionId = $_GET['id'];
    $version = Project::getVersionById($versionId);
    $projectId = $version['project_id'];
    $project = Project::getById($projectId);

    if ($project['client_id'] != $clientId) {
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

        $releaseDuplicate = Project::getVersionByName($projectId, $name, $versionId);
        if ($releaseDuplicate)
            $alreadyExists = true;

        if (!$emptyName && !$alreadyExists) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            Project::updateVersionById($versionId, $name, $description, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Project Version ' . $name, $currentDate);

            header('Location: /yongo/administration/project/versions/' . $projectId);
        }
    }

    $menuSelectedCategory = 'project';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Project Version';

    require_once __DIR__ . '/../../../../Resources/views/administration/project/version/Edit.php';