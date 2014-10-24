<?php

namespace Ubirimi\Yongo\Controller\Issue\Move;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class GetProjectIssueTypesController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $request->request->get('id');
        $moveToIssueTypes = $this->getRepository(YongoProject::class)->getIssueTypes($projectId, 0, 'array');

        return new JsonResponse(json_encode($moveToIssueTypes));

    }
}
