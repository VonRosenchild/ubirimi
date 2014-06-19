<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\User\User;

class UsersController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $users = User::getAll(array('sort_by' => 'user.date_created', 'sort_order' => 'desc', 'limit' => 50));

        $selectedOption = 'users';

        return $this->render(__DIR__ . '/../../Resources/views/administration/Users.php', get_defined_vars());
    }
}
