<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ConfirmDeleteAdministratorController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $Id = $request->get('id');

        $svnAdministrators = $this->getRepository(SvnRepository::class)->getAdministratorsByClientId($clientId);
        if ($svnAdministrators && 1 == $svnAdministrators->num_rows) {
            return new Response('It is not possible to delete the last SVN Administrator.');
        };

        return new Response('Are you sure you want to delete this SVN Administrator?');
    }
}
