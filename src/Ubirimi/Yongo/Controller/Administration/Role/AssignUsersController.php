<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionRoleId = $_POST['role_id'];
    $userArray = $_POST['user_arr'];
    $projectId = $_POST['project_id'];

    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Project::deleteUsersByPermissionRole($projectId, $permissionRoleId);
    Project::addUsersForPermissionRole($projectId, $permissionRoleId, $userArray, $currentDate);