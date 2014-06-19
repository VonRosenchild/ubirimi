<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\Log;

class LogController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $logs = Log::getAll();

        $selectedOption = 'log';

        return $this->render(__DIR__ . '/../../Resources/views/administration/Log.php', get_defined_vars());
    }
}
