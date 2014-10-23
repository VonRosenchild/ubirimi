<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\ServerSettings;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class MaintenanceController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkSuperUserIsLoggedIn();

        if ($request->request->has('update')) {
            $message = $_POST['maintenance_message'];

            ServerSettings::updateMaintenanceMessage($message);

            return new RedirectResponse('/administration/maintenance');
        }

        $serverSettings = ServerSettings::get();

        return $this->render(__DIR__ . '/../../Resources/views/administration/Maintenance.php', get_defined_vars());
    }
}
