<?php

namespace Ubirimi\Yongo\Controller\Administration\PermissionScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Scheme;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionSchemes = $this->getRepository('yongo.permission.scheme')->ggetByClientId($session->get('client/id'));
        $menuSelectedCategory = 'issue';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Permission Schemes';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/permission_scheme/List.php', get_defined_vars());
    }
}
