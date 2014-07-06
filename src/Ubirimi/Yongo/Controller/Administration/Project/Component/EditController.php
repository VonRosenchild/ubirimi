<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $componentId = $_GET['id'];
    $component = Project::getComponentById($componentId);
    $projectId = $component['project_id'];
    $project = Project::getById($projectId);

    if ($project['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $alreadyExists = false;

    if (isset($_POST['edit_component'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $leader = Util::cleanRegularInputField($_POST['leader']);

        if (empty($name))
            $emptyName = true;

        $components_duplicate = Project::getComponentByName($projectId, $name, $componentId);
        if ($components_duplicate)
            $alreadyExists = true;

        if (!$emptyName && !$alreadyExists) {
            $currentDate = Util::getServerCurrentDateTime();

            Project::updateComponentById($componentId, $name, $description, $leader, $currentDate);
            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Project Component ' . $name, $currentDate);

            header('Location: /yongo/administration/project/components/' . $projectId);
        }
    }

    $users = Client::getUsers($clientId);
    $menuSelectedCategory = 'project';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Project Component';

    require_once __DIR__ . '/../../../../Resources/views/administration/project/component/Edit.php';