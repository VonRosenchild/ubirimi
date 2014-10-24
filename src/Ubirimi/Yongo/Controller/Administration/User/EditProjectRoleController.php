<?php

namespace Ubirimi\Yongo\Controller\Administration\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Role;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class EditProjectRoleController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->get('id');

        $users = $this->getRepository(UbirimiClient::class)->getUsers($session->get('client/id'));
        $user = $this->getRepository(UbirimiUser::class)->getById($userId);
        $projects = $this->getRepository(YongoProject::class)->getByClientId($session->get('client/id'));
        $roles = $this->getRepository(Role::class)->getByClient($session->get('client/id'));
        $groups = $this->getRepository(UbirimiGroup::class)->getByUserIdAndProductId($userId, SystemProduct::SYS_PRODUCT_YONGO);
        $groupIds = array();
        while ($groups && $group = $groups->fetch_array(MYSQLI_ASSOC)) {
            $groupIds[] = $group['id'];
        }

        if ($request->request->has('edit_user_project_role')) {
            $currentDate = Util::getServerCurrentDateTime();
            $this->getRepository(Role::class)->deleteRolesForUser($userId);
            foreach ($request->request as $key => $value) {
                if (substr($key, 0, 5) == 'role_') {
                    $data = str_replace('role_', '', $key);
                    $params = explode('_', $data);
                    $this->getRepository(Role::class)->addProjectRoleForUser($userId, $params[0], $params[1], $currentDate);
                }
            }

            return new RedirectResponse('/yongo/administration/user/project-roles/' . $userId);
        }

        $menuSelectedCategory = 'user';
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update User Project Roles';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/user/EditProjectRole.php', get_defined_vars());
    }
}
