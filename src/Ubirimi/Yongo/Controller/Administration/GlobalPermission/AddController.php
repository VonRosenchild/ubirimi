<?php

namespace Ubirimi\Yongo\Controller\Administration\GlobalPermission;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;



class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $allGroups = $this->getRepository('ubirimi.user.group')->getByClientIdAndProductId($session->get('client/id'), SystemProduct::SYS_PRODUCT_YONGO);
        $globalPermissions = GlobalPermission::getAllByProductId(SystemProduct::SYS_PRODUCT_YONGO);

        if ($request->request->has('confirm_new_permission')) {
            $permissionId = $request->request->get('permission');
            $groupId = $request->request->get('group');
            $currentDate = Util::getServerCurrentDateTime();
            $group = $this->getRepository('ubirimi.user.group')->getMetadataById($groupId);
            $permission = GlobalPermission::getById($permissionId);

            $date = Util::getServerCurrentDateTime();

            // check if the group is already added
            $permissionData = GlobalPermission::getDataByPermissionIdAndGroupId(
                $session->get('client/id'),
                $permissionId,
                $groupId
            );

            if (!$permissionData) {
                GlobalPermission::addDataForGroupId($session->get('client/id'), $permissionId, $groupId, $date);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'ADD Yongo Global Permission ' . $permission['name'] . ' to group ' . $group['name'],
                    $currentDate
                );
            }

            return new RedirectResponse('/yongo/administration/global-permissions');
        }

        $menuSelectedCategory = 'user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Global Permission';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/global_permission/Add.php', get_defined_vars());
    }
}
