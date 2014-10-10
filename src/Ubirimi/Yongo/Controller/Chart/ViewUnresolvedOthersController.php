<?php

namespace Ubirimi\Yongo\Controller\Chart;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Repository\Client;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Permission\Permission;

class ViewUnresolvedOthersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $clientId = $session->get('client/id');
            $loggedInUserId = $session->get('user/id');
        } else {
            $clientId = Client::getClientIdAnonymous();
            $loggedInUserId = null;
        }

        $projectId = $request->request->get('id');
        $selectedProjectId = $projectId;
        if ($projectId == -1) {
            $projects = array();
            $projectsForBrowsing = Client::getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS, 'array');

            for ($i = 0; $i < count($projectsForBrowsing); $i++) {
                $projects[] = $projectsForBrowsing[$i]['id'];
            }
        } else {
            $projects = array((int)$projectId);
        }

        $issueQueryParameters = array('issues_per_page' => 20, 'resolution' => array(-2),
            'sort' => 'code', 'sort_order' => 'desc', 'project' => $projects);

        if ($loggedInUserId) {
            $issueQueryParameters['not_assignee'] = $loggedInUserId;
        }

        $issuesUnresolvedOthers = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters, $loggedInUserId, null, $loggedInUserId);

        $renderParameters = array('issues' => $issuesUnresolvedOthers, 'render_checkbox' => false, 'show_header' =>true);
        $renderColumns = array('code', 'summary', 'priority', 'assignee');

        $projects = Client::getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS, 'array');

        $projectIdsNames = array();
        for ($i = 0; $i < count($projects); $i++) {
            $projectIdsNames[] = array($projects[$i]['id'], $projects[$i]['name']);
        }

        return $this->render(__DIR__ . '/../../Resources/views/charts/ViewUnresolvedOthers.php', get_defined_vars());
    }
}
