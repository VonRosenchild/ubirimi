<?php

namespace Ubirimi\Yongo\Controller\Issue\Link;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueLinkType;

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
            $types = IssueLinkType::getByClientId($clientId);
            $issueQueryParameters = array('project' => $projectId);
            $issues = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters, $loggedInUserId);
        }

        return $this->render(__DIR__ . '/../../../Resources/views/issue/link/ViewDialog.php', get_defined_vars());
    }
}
