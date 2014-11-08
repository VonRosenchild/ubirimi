<?php

namespace Ubirimi\Documentador\Controller\Administration\SpaceTools;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class PermissionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'doc_spaces';

        $spaceId = $request->get('id');
        $space = $this->getRepository(Space::class)->getById($spaceId);

        if ($space['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $documentatorSettings = $this->getRepository(UbirimiClient::class)->getDocumentadorSettings($clientId);
        $anonymousAccessSettings = $this->getRepository(Space::class)->getAnonymousAccessSettings($spaceId);

        $usersWithPermissionForSpace = $this->getRepository(Space::class)->getUsersWithPermissions($spaceId);
        $groupsWithPermissionForSpace = $this->getRepository(Space::class)->getGroupsWithPermissions($spaceId);

        return $this->render(__DIR__ . '/../../../Resources/views/administration/spacetools/Permission.php', get_defined_vars());
    }
}