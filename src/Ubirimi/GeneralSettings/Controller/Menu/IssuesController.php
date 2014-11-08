<?php

namespace Ubirimi\General\Controller\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class IssuesController extends UbirimiController
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
        for ($i = 0; $i < count($projectsMenu); $i++)
            $projectsForBrowsing[$i] = $projectsMenu[$i]['id'];

        $hasCreateIssuePermission = false;
        if (count($projectsForBrowsing)) {
            $hasCreateIssuePermission = $this->getRepository(YongoProject::class)->userHasPermission(
                $projectsForBrowsing,
                Permission::PERM_CREATE_ISSUE,
                $session->get('user/id')
            );
        }

        $recentIssues = $session->get('yongo/recent_issues');

        return $this->render(__DIR__ . '/../../Resources/views/menu/Issues.php', get_defined_vars());
    }
}