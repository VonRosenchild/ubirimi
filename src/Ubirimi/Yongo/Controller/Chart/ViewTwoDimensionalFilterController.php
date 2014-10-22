<?php

namespace Ubirimi\Yongo\Controller\Chart;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;

class ViewTwoDimensionalFilterController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $clientId = $session->get('client/id');
            $loggedInUserId = $session->get('user/id');
        } else {
            $clientId = $this->getRepository('ubirimi.general.client')->getClientIdAnonymous();
            $loggedInUserId = null;
        }
        $projects = $this->getRepository('ubirimi.general.client')->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS, 'array');

        $projectId = $request->request->get('id');

        $projectIdsNames = array();
        for ($i = 0; $i < count($projects); $i++) {
            $projectIdsNames[] = array($projects[$i]['id'], $projects[$i]['name']);
        }

        $usersAsAssignee = $this->getRepository('ubirimi.user.user')->getByClientId($clientId);
        $issueStatuses = $this->getRepository('yongo.issue.settings')->getAllIssueSettings('status', $clientId, 'array');

        $twoDimensionalData = $this->getRepository('yongo.issue.issue')->get2DimensionalFilter($projectId, 'array');

        return $this->render(__DIR__ . '/../../Resources/views/charts/ViewTwoDimensionalFilter.php', get_defined_vars());
    }
}
