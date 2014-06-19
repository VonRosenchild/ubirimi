<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];
    $project = Project::getById($projectId);
    $users = Client::getUsers($clientId);

    $emptyName = false;
    $alreadyExists = false;

    if (isset($_POST['confirm_new_component'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $leader = Util::cleanRegularInputField($_POST['leader']);

        if (empty($name))
            $emptyName = true;

        $components_duplicate = Project::getComponentByName($projectId, $name);
        if ($components_duplicate)
            $alreadyExists = true;

        if (!$emptyName && !$alreadyExists) {
            if ($leader == -1) {
                $leader = null;
            }
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            Project::addComponent($projectId, $name, $description, $leader, null, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Project Component ' . $name, $currentDate);

            header('Location: /yongo/administration/project/components/' . $projectId);
        }
    }
    $menuSelectedCategory = 'project';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Project Component';

    require_once __DIR__ . '/../../../../Resources/views/administration/project/component/Add.php';