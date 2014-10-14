<?php

namespace Ubirimi\SVNHosting\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use ubirimi\svn\SVNRepository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListUserController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');
        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);

        $isSVNAdministrator = $session->get('user/svn_administrator_flag');
        $menuSelectedCategory = 'svn';

        $svnRepos = SVNRepository::getRepositoriesByUserId($clientId, $loggedInUserId, 'array');
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_SVN_HOSTING_NAME. ' / My Repositories';

        return $this->render(__DIR__ . '/../Resources/views/ListUser.php', get_defined_vars());
    }
}
