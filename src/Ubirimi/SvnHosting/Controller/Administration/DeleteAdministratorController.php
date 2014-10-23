<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteAdministratorController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $Id = $request->request->get('id');

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $this->getRepository('svnHosting.repository')->deleteAdministratorById($clientId, $Id);

        // if the deleted administrator is the logged in user than refresh the session data
        if ($Id == $loggedInUserId) {
            $session->set('user/svn_administrator_flag', 0);
        }

        return new Response('');
    }
}
