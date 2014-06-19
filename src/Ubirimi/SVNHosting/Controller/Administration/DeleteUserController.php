<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use ubirimi\svn\SVNRepository;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteUserController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->request->get('id');
        SVNRepository::deleteUserById($Id);

        SVNRepository::updateHtpasswd($session->get('selected_svn_repo_id'), $session->get('client/company_domain'));
        SVNRepository::updateAuthz();

        return new Response('');
    }
}
