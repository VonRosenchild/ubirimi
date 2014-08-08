<?php

namespace Ubirimi\HelpDesk\Controller\Administration\Organization;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DeleteDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        return $this->render(__DIR__ . '/../../../Resources/views/administration/organization/DeleteDialog.php', get_defined_vars());
    }
}
