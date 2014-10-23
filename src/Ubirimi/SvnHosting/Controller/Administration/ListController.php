<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\Repository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'svn';
        $svnRepos = Repository::getAllByClientId($clientId, 'array');
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);

        return $this->render(__DIR__ . '/../../Resources/views/administration/List.php', get_defined_vars());
    }
}
