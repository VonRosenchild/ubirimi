<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $userId = $_GET['id'];

    $users = Client::getUsers($clientId);
    $user = User::getById($userId);
    $projects = Project::getByClientId($clientId);
    $roles = PermissionRole::getByClient($clientId);
    $groups = Group::getByUserIdAndProductId($userId, SystemProduct::SYS_PRODUCT_YONGO);
    $groupIds = array();
    while ($groups && $group = $groups->fetch_array(MYSQLI_ASSOC)) {
        $groupIds[] = $group['id'];
    }

    if (isset($_POST['edit_user_project_role'])) {
        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
        PermissionRole::deleteRolesForUser($userId);
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 5) == 'role_') {
                $data = str_replace('role_', '', $key);
                $params = explode('_', $data);
                PermissionRole::addProjectRoleForUser($userId, $params[0], $params[1], $currentDate);
            }
        }
        header('Location: /yongo/administration/user/project-roles/' . $userId);
    }

    $menuSelectedCategory = 'user';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update User Project Roles';

    require_once __DIR__ . '/../../../Resources/views/administration/user/EditProjectRole.php';