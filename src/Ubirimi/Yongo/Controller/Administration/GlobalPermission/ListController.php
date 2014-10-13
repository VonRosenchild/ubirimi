<?php

namespace Ubirimi\Yongo\Controller\Administration\GlobalPermission;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Client;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $users = $this->getRepository('ubirimi.general.client')->getUsers($session->get('client/id'));

        $globalPermissions = GlobalPermission::getAllByProductId(SystemProduct::SYS_PRODUCT_YONGO);
        $menuSelectedCategory = 'user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Global Permissions';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/global_permission/List.php', get_defined_vars());
    }
}
