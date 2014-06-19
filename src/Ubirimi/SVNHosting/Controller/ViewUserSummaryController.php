<?php

namespace Ubirimi\SVNHosting\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use ubirimi\svn\SVNRepository;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\UbirimiController;

class ViewUserSummaryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'svn';
        $svnRepoId = $request->get('id');
        $clientId = $session->get('client/id');

        $svnRepo = SVNRepository::getById($svnRepoId);

        if ($svnRepo['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $svnRepoUserList = SVNRepository::getUserList($svnRepoId, 'array');
        $isSVNAdministrator = $session->get('user/svn_administrator_flag');
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_SVN_HOSTING_NAME. ' / ' . $svnRepo['code'] . ' / Users';

        return $this->render(__DIR__ . '/../Resources/views/ViewUserSummary.php', get_defined_vars());
    }
}
