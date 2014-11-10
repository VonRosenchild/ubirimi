<?php

namespace Ubirimi\SvnHosting\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ConfirmSetPermissionsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->get('id');
        $repoId = $request->get('repo_id');
        $user = $this->getRepository(SvnRepository::class)->getUserById($userId);
        $svnRepo = $this->getRepository(SvnRepository::class)->getById($repoId);

        return $this->render(__DIR__ . '/../Resources/views/ConfirmSetPermissions.php', get_defined_vars());
    }
}
