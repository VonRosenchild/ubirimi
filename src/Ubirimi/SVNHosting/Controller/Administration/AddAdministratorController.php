<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use ubirimi\svn\SVNRepository;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\User\User;

class AddAdministratorController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $menuSelectedCategory = 'svn';
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING);
        $regularUsers = User::getNotSVNAdministrators($clientId);
        $noUsersSelected = false;

        if (isset($_POST['confirm_new_svn_administrator'])) {
            $users = $request->request->get('user');

            if ($users) {
                SVNRepository::addAdministrator($users);

                return new RedirectResponse('/svn-hosting/administration/administrators');
            } else {
                $noUsersSelected = true;
            }
        }

        return $this->render(__DIR__ . '/../../Resources/views/administration/AddAdministrator.php', get_defined_vars());
    }
}
