<?php

namespace Ubirimi\Yongo\Controller\Issue\Filter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddSubscriptionDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        return $this->render(__DIR__ . '/../../../Resources/views/filter/AddSubscriptionDialog.php', get_defined_vars());


    }
}
