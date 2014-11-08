<?php

namespace Ubirimi\General\Controller\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;
use Ubirimi\Yongo\Repository\Permission\Permission;

class FiltersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {

        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;
        }

        $projectsMenu = $this->getRepository(UbirimiClient::class)->getProjectsByPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            Permission::PERM_BROWSE_PROJECTS,
            'array'
        );

        $projectsForBrowsing = array();
        for ($i = 0; $i < count($projectsMenu); $i++) {
            $projectsForBrowsing[$i] = $projectsMenu[$i]['id'];
        }

        $customFilters = $this->getRepository(IssueFilter::class)->getAllByUser($session->get('user/id'));

        return $this->render(__DIR__ . '/../../Resources/views/menu/Filters.php', get_defined_vars());
    }
}
