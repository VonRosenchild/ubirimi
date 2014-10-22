<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\ServerSettings;

class MaintenanceController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        if (isset($_POST['update'])) {
            $message = $_POST['maintenance_message'];

            ServerSettings::updateMaintenanceMessage($message);

            return new RedirectResponse('/administration/maintenance');
        }

        $serverSettings = ServerSettings::get();

        return $this->render(__DIR__ . '/../../Resources/views/administration/Maintenance.php', get_defined_vars());
    }
}
