<?php

namespace Ubirimi\Yongo\Controller\Administration\Workflow\IssueTypeScheme;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $Id = $request->request->get('id');

        IssueTypeScheme::deleteDataByIssueTypeSchemeId($Id);
        IssueTypeScheme::deleteById($Id);

        return new Response('');
    }
}
