<?php

namespace Ubirimi\SVNHosting\Controller\Administration;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SvnHosting\Repository\Repository;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class ConfirmImportUsersController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $users = $this->getRepository('ubirimi.general.client')->getUsers($clientId, null, 'array', 0);

        $existingUsers = Repository::getUserList($session->get('selected_svn_repo_id'), 'array');
        $importableUsers = array();

        foreach ($users as $user) {
            $found = false;
            if ($existingUsers) {
                foreach ($existingUsers as $existingUser) {
                    if ($user['id'] == $existingUser['user_id']) {
                        $found = true;
                        break;
                    }
                }
            }

            if (!$found) {
                $importableUsers[] = $user;
            }
        }

        return $this->render(__DIR__ . '/../../Resources/views/administration/ConfirmImportUsers.php', get_defined_vars());
    }
}
