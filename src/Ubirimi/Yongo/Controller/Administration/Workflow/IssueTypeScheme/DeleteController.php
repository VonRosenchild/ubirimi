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

        $this->getRepository(IssueTypeScheme::class)->deleteDataByIssueTypeSchemeId($Id);
        $this->getRepository(IssueTypeScheme::class)->deleteById($Id);

        return new Response('');
    }
}
