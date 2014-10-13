<?php

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Board;
use Ubirimi\Agile\Repository\Sprint;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddIssueController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $sprintId = $request->request->get('id');
        $issueIdArray = $request->request->get('issue_id');

        if ($sprintId && $issueIdArray) {
            $this->getRepository('agile.board.board')->deleteIssuesFromSprints($issueIdArray);
            Sprint::addIssues($sprintId, $issueIdArray, $session->get('user/id'));
        }

        return new Response('');
    }
}
