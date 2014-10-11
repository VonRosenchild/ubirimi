<?php

namespace Ubirimi\Yongo\Controller\Administration\PermissionScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Permission\Scheme;

class DeleteDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $permissionSchemeDataId = $request->request->get('permission_scheme_data_id');
        Scheme::deleteDataById($permissionSchemeDataId);

        $currentDate = Util::getServerCurrentDateTime();

        Log::add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_YONGO,
            $session->get('user/id'),
            'DELETE Yongo Permission Scheme Data',
            $currentDate
        );

        return new Response('');
    }
}
