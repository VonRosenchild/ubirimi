<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class LogController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $logs = $this->getRepository(UbirimiLog::class)->getAll();

        $selectedOption = 'log';

        return $this->render(__DIR__ . '/../../Resources/views/administration/Log.php', get_defined_vars());
    }
}
