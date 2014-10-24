<?php

namespace Ubirimi\Documentador\Controller\Administration\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $hasDocumentatorGlobalAdministrationPermission = $session->get('user/documentator/is_global_administrator');
        $hasDocumentatorGlobalSystemAdministrationPermission = $session->get('user/documentator/is_global_system_administrator');

        if ($hasDocumentatorGlobalAdministrationPermission || $hasDocumentatorGlobalSystemAdministrationPermission) {
            $spaces = $this->getRepository(Space::class)->getByClientId($clientId);
        } else {
            $spaces = $this->getRepository(Space::class)->getWithAdminPermissionByUserId($clientId, $loggedInUserId);
        }
        $clientSettings = $session->get('client/settings');

        $menuSelectedCategory = 'doc_spaces';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/space/List.php', get_defined_vars());
    }
}

