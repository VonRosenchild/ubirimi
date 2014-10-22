<?php

namespace Ubirimi\Yongo\Controller\Administration\PermissionScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionSchemeId = $request->request->get('id');

        $this->getRepository('yongo.permission.scheme')->gdeleteDataByPermissionSchemeId($permissionSchemeId);
        $this->getRepository('yongo.permission.scheme')->gdeleteById($permissionSchemeId);

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('ubirimi.general.log')->add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Permission Scheme',
            $currentDate
        );
    }
}
