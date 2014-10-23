<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteUserController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->request->get('id');
        $this->getRepository('svnHosting.repository')->deleteUserById($Id);

        $this->getRepository('svnHosting.repository')->updateHtpasswd($session->get('selected_svn_repo_id'), $session->get('client/company_domain'));
        $this->getRepository('svnHosting.repository')->updateAuthz();

        return new Response('');
    }
}
