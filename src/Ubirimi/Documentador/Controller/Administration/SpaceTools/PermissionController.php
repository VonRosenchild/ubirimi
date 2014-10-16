<?php

namespace Ubirimi\Documentador\Controller\Administration\SpaceTools;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class PermissionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'doc_spaces';

        $spaceId = $_GET['id'];
        $space = $this->getRepository('documentador.space.space')->getById($spaceId);

        if ($space['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }

        $documentatorSettings = $this->getRepository('ubirimi.general.client')->getDocumentatorSettings($clientId);
        $anonymousAccessSettings = $this->getRepository('documentador.space.space')->getAnonymousAccessSettings($spaceId);

        $usersWithPermissionForSpace = $this->getRepository('documentador.space.space')->getUsersWithPermissions($spaceId);
        $groupsWithPermissionForSpace = $this->getRepository('documentador.space.space')->getGroupsWithPermissions($spaceId);

        require_once __DIR__ . '/../../../Resources/views/administration/spacetools/Permission.php';
    }
}