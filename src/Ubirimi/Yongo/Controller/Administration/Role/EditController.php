<?php

namespace Ubirimi\Yongo\Controller\Administration\Role;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

use Ubirimi\Yongo\Repository\Permission\Role;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionRoleId = $request->get('id');
        $perm_role = $this->getRepository('yongo.permission.role')->getById($permissionRoleId);

        if ($perm_role['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $alreadyExists = false;

        if ($request->request->has('confirm_edit_perm_role')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($name))
                $emptyName = true;

            $role = $this->getRepository('yongo.permission.role')->getByName($session->get('client/id'), $name, $permissionRoleId);
            if ($role)
                $alreadyExists = true;

            if (!$emptyName && !$alreadyExists) {
                $date = Util::getServerCurrentDateTime();
                $this->getRepository('yongo.permission.role')->gupdateById($permissionRoleId, $name, $description, $date);

                $this->getRepository('ubirimi.general.log')->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_YONGO,
                    $session->get('user/id'),
                    'UPDATE Yongo Project Role ' . $name,
                    $date
                );

                return new RedirectResponse('/yongo/administration/roles');
            }
        }

        $menuSelectedCategory = 'user';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Project Role';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/role/Edit.php', get_defined_vars());
    }
}
