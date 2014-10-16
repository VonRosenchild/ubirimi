<?php

namespace Ubirimi\Yongo\Controller\Administration\PermissionScheme;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Scheme;


use Ubirimi\Repository\User\User;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Permission\Role;

class AddDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionSchemeId = $request->get('perm_scheme_id');
        $permissionId = $request->get('id');

        $permissionScheme = $this->getRepository('yongo.permission.scheme')->getMetaDataById($permissionSchemeId);
        $permissions = Permission::getAll();

        $users = $this->getRepository('ubirimi.user.user')->getByClientId($session->get('client/id'));
        $groups = $this->getRepository('ubirimi.user.group')->getByClientIdAndProductId($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO);
        $roles = $this->getRepository('yongo.permission.role')->getByClient($session->get('client/id'));

        if ($request->request->has('confirm_new_data')) {

            $sysPermissionIds = $request->request->get('permission');

            $permissionType = $request->request->get('type');

            $user = $request->request->get('user');
            $group = $request->request->get('group');
            $role = $request->request->get('role');
            $currentDate = Util::getServerCurrentDateTime();

            if ($permissionType) {
                for ($i = 0; $i < count($sysPermissionIds); $i++){
                    // check for duplicate information
                    $duplication = false;
                    $dataPermission = $this->getRepository('yongo.permission.scheme')->getDataByPermissionSchemeIdAndPermissionId(
                        $permissionSchemeId,
                        $sysPermissionIds[$i]
                    );

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
                        $this->getRepository('yongo.permission.scheme')->gaddData(
                            $permissionSchemeId,
                            $sysPermissionIds[$i],
                            $permissionType,
                            $role,
                            $group,
                            $user,
                            $currentDate
                        );

                        $this->getRepository('ubirimi.general.log')->add(
                            $session->get('client/id'),
                            SystemProduct::SYS_PRODUCT_YONGO,
                            $session->get('user/id'),
                            'ADD Yongo Permission Scheme Data',
                            $currentDate
                        );
                    }
                }

            }

            return new RedirectResponse('/yongo/administration/permission-scheme/edit/' . $permissionSchemeId);
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Permission Data';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/permission_scheme/AddData.php', get_defined_vars());
    }
}
