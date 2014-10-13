<?php

namespace Ubirimi\Yongo\Controller\Administration\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Group\Group;
use Ubirimi\Repository\User\User;
use Ubirimi\Yongo\Repository\Permission\Role;
use Ubirimi\Yongo\Repository\Project\Project;

class ListProjectRoleController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->get('id');

        $users = $this->getRepository('ubirimi.general.client')->getUsers($session->get('client/id'));
        $user = $this->getRepository('ubirimi.user.user')->getById($userId);
        $projects = $this->getRepository('yongo.project.project')->getByClientId($session->get('client/id'));
        $roles = $this->getRepository('yongo.permission.role')->ggetByClient($session->get('client/id'));
        $groups = $this->getRepository('ubirimi.user.group')->getByUserIdAndProductId($userId, SystemProduct::SYS_PRODUCT_YONGO);
        $groupIds = array();
        while ($groups && $group = $groups->fetch_array(MYSQLI_ASSOC)) {
            $groupIds[] = $group['id'];
        }

        $menuSelectedCategory = 'user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / User Project Roles';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/user/ListProjectRole.php', get_defined_vars());
    }
}
