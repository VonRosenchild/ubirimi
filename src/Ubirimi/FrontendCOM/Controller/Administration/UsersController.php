<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\UbirimiController;
use Ubirimi\Util;

class UsersController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $users = $this->getRepository('ubirimi.user.user')->getAll(array('sort_by' => 'user.date_created', 'sort_order' => 'desc', 'limit' => 50));

        $selectedOption = 'users';

        return $this->render(__DIR__ . '/../../Resources/views/administration/Users.php', get_defined_vars());
    }
}
