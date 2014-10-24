<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Permission\Permission;


class QuickSearchController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $searchQuery = $request->request->get('code');

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $projects = $this->getRepository(UbirimiClient::class)->getProjectsByPermission($clientId, $session->get('user/id'), Permission::PERM_BROWSE_PROJECTS, 'array');
        $projects = Util::array_column($projects, 'id');

        // search first for a perfect match
        $issueResult = $this->getRepository(Issue::class)->getByParameters(array('project' => $projects, 'code_nr' => $searchQuery), $loggedInUserId, null, $loggedInUserId);

        if ($issueResult) {
            $issue = $issueResult->fetch_array(MYSQLI_ASSOC);
            return new Response($issue['id']);
        } else {
            return new Response('-1');
        }
    }
}