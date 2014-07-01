<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Repository\Log;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\Permission;
    use Ubirimi\Yongo\Repository\Permission\PermissionRole;
    use Ubirimi\Yongo\Repository\Permission\PermissionScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $permissionSchemeId = $_GET['perm_scheme_id'];
    $permissionId = isset($_GET['id']) ? $_GET['id'] : null;

    $permissionScheme = PermissionScheme::getMetaDataById($permissionSchemeId);
    $permissions = Permission::getAll();

    $users = User::getByClientId($clientId);
    $groups = Group::getByClientIdAndProductId($clientId, SystemProduct::SYS_PRODUCT_YONGO);
    $roles = PermissionRole::getByClient($clientId);

    if (isset($_POST['confirm_new_data'])) {

        $sysPermissionIds = $_POST['permission'];
        
        $permissionType = ($_POST['type']) ? $_POST['type'] : null;

        $user = $_POST['user'];
        $group = $_POST['group'];
        $role = $_POST['role'];
        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

        if ($permissionType) {
            for ($i = 0; $i < count($sysPermissionIds); $i++){
                // check for duplicate information
                $duplication = false;
                $dataPermission = PermissionScheme::getDataByPermissionSchemeIdAndPermissionId($permissionSchemeId, $sysPermissionIds[$i]);
                if ($dataPermission) {

                    while ($data = $dataPermission->fetch_array(MYSQLI_ASSOC)) {

                        if (isset($data['group_id']) && $group && $data['group_id'] == $group)
                            $duplication = true;
                        if ($data['user_id'] && $data['user_id'] == $user)
                            $duplication = true;
                        if ($data['permission_role_id'] && $data['permission_role_id'] == $role) {
                            $duplication = true;
                        }

                        if ($permissionType == Permission::PERMISSION_TYPE_PROJECT_LEAD)
                            if ($data['project_lead'])
                                $duplication = true;
                        if ($permissionType == Permission::PERMISSION_TYPE_CURRENT_ASSIGNEE)
                            if ($data['current_assignee'])
                                $duplication = true;
                        if ($permissionType == Permission::PERMISSION_TYPE_REPORTER)
                            if ($data['reporter'])
                                $duplication = true;
                    }
                }

                if (!$duplication) {
                    PermissionScheme::addData($permissionSchemeId, $sysPermissionIds[$i], $permissionType, $role, $group, $user, $currentDate);

                    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Permission Scheme Data', $currentDate);
                }
            }

        }

        header('Location: /yongo/administration/permission-scheme/edit/' . $permissionSchemeId);
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Permission Data';

    require_once __DIR__ . '/../../../Resources/views/administration/permission_scheme/AddData.php';