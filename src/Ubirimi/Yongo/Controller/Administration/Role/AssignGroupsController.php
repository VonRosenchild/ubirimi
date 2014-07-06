<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionRoleId = $_POST['role_id'];
    $groupArrayIds = $_POST['group_arr'];
    $projectId = $_POST['project_id'];

    $currentDate = Util::getServerCurrentDateTime();
    Project::deleteGroupsByPermissionRole($projectId, $permissionRoleId);
    Project::addGroupsForPermissionRole($projectId, $permissionRoleId, $groupArrayIds, $currentDate);