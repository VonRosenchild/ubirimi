<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteUserController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->request->get('id');
        $this->getRepository(SvnRepository::class)->deleteUserById($Id);

        $this->getRepository(SvnRepository::class)->updateHtpasswd($session->get('selected_svn_repo_id'), $session->get('client/company_domain'));
        $this->getRepository(SvnRepository::class)->updateAuthz();

        return new Response('');
    }
}
