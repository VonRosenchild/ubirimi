<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\Repository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewUserController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'svn';
        $svnRepoId = $request->get('id');
        $session->set('selected_svn_repo_id', $svnRepoId);

        $svnRepo = Repository::getById($svnRepoId);
        if ($svnRepo['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $svnRepoUserList = Repository::getUserList($session->get('selected_svn_repo_id'), 'array');

        $isSVNAdministrator = $session->get('user/svn_administrator_flag');
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);

        return $this->render(__DIR__ . '/../../Resources/views/administration/ViewUser.php', get_defined_vars());
    }
}
