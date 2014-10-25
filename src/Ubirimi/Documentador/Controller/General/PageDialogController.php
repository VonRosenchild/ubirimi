<?php

namespace Ubirimi\Documentador\Controller\General;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class PageDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $type = $request->get('type');

        return $this->render(__DIR__ . '/../../Resources/views/page/Dialog.php', get_defined_vars());
    }
}
