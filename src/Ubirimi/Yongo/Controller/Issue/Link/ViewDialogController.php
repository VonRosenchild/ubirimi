<?php

namespace Ubirimi\Yongo\Controller\Issue\Link;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\LinkType;

class ViewDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $projectId = $request->get('project_id');
        $issueId = $request->get('issue_id');
        $linkPossible = $request->get('link_possible');

        if ($linkPossible) {
            $types = LinkType::getByClientId($clientId);
            $issueQueryParameters = array('project' => $projectId);
            $issues = $this::getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters, $loggedInUserId);
        }

        return $this->render(__DIR__ . '/../../../Resources/views/issue/link/ViewDialog.php', get_defined_vars());
    }
}
