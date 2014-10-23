<?php

namespace Ubirimi\SVNHosting\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\Repository;
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

        Repository::updateUserPermissions($repoId, $Id, $hasRead, $hasWrite);

        Repository::updateHtpasswd($repoId, $session->get('client/company_domain'));
        Repository::updateAuthz();

        return new Response('');
    }
}
