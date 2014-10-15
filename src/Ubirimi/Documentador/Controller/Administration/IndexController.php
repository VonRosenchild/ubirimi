<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class IndexController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');
        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'doc_administration';

        $spacesWithAdminPermission = Space::getWithAdminPermissionByUserId($clientId, $loggedInUserId);

        $hasDocumentatorGlobalAdministrationPermission = $session->get('user/documentator/is_global_administrator');
        $hasDocumentatorGlobalSystemAdministrationPermission = $session->get('user/documentator/is_global_system_administrator');

        require_once __DIR__ . '/../../Resources/views/administration/Index.php';
    }
}