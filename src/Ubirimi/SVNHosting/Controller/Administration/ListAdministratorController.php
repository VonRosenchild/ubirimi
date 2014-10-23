<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\Repository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListAdministratorController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $menuSelectedCategory = 'svn';

        $clientId = $session->get('client/id');

        $svnAdministrators = Repository::getAdministratorsByClientId($clientId);
        $isSVNAdministrator = $session->get('user/svn_administrator_flag');
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);

        return $this->render(__DIR__ . '/../../Resources/views/administration/ListAdministrator.php', get_defined_vars());
    }
}
