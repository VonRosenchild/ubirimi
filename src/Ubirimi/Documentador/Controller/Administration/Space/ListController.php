<?php

namespace Ubirimi\Documentador\Controller\Administration\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $hasDocumentatorGlobalAdministrationPermission = $session->get('user/documentator/is_global_administrator');
        $hasDocumentatorGlobalSystemAdministrationPermission = $session->get('user/documentator/is_global_system_administrator');

        if ($hasDocumentatorGlobalAdministrationPermission || $hasDocumentatorGlobalSystemAdministrationPermission) {
            $spaces = $this->getRepository('documentador.space.space')->getByClientId($clientId);
        } else {
            $spaces = $this->getRepository('documentador.space.space')->getWithAdminPermissionByUserId($clientId, $loggedInUserId);
        }
        $clientSettings = $session->get('client/settings');

        $menuSelectedCategory = 'doc_spaces';

        require_once __DIR__ . '/../../../Resources/views/administration/space/List.php';
    }
}

