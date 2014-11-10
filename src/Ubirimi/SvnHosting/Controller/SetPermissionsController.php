<?php

namespace Ubirimi\SvnHosting\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SetPermissionsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->request->get('id');
        $repoId = Util::cleanRegularInputField($request->request->get('repo_id'));
        $hasRead = Util::cleanRegularInputField($request->request->get('has_read'));
        $hasWrite = Util::cleanRegularInputField($request->request->get('has_write'));

        $this->getRepository(SvnRepository::class)->updateUserPermissions($repoId, $Id, $hasRead, $hasWrite);

        $this->getRepository(SvnRepository::class)->updateHtpasswd($repoId, $session->get('client/company_domain'));
        $this->getRepository(SvnRepository::class)->updateAuthz();

        return new Response('');
    }
}
