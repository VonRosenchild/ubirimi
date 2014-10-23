<?php

namespace Ubirimi\SVNHosting\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ConfirmSetPermissionsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->get('id');
        $repoId = $request->get('repo_id');
        $user = $this->getRepository('svnHosting.repository')->getUserById($userId);
        $svnRepo = $this->getRepository('svnHosting.repository')->getById($repoId);

        return $this->render(__DIR__ . '/../Resources/views/ConfirmSetPermissions.php', get_defined_vars());
    }
}
