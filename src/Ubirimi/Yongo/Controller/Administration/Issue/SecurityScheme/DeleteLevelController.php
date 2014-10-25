<?php

namespace Ubirimi\Yongo\Controller\Administration\Issue\SecurityScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;

class DeleteLevelController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueSecuritySchemeLevelId = $request->request->get('id');
        $newIssueSecuritySchemeLevelId = $request->request->get('new_level_id');

        $this->getRepository(Issue::class)->updateSecurityLevel(
            $session->get('client/id'),
            $issueSecuritySchemeLevelId,
            $newIssueSecuritySchemeLevelId
        );

        \deleteLevelById($issueSecuritySchemeLevelId);

        return new Response('');
    }
}
