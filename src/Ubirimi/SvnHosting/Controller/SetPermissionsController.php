<?php

namespace Ubirimi\SVNHosting\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

        $this->getRepository('svnHosting.repository')->updateUserPermissions($repoId, $Id, $hasRead, $hasWrite);

        $this->getRepository('svnHosting.repository')->updateHtpasswd($repoId, $session->get('client/company_domain'));
        $this->getRepository('svnHosting.repository')->updateAuthz();

        return new Response('');
    }
}
