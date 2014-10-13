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

        $logs = $this->getRepository('ubirimi.general.log')->getAll();

        $selectedOption = 'log';

        return $this->render(__DIR__ . '/../../Resources/views/administration/Log.php', get_defined_vars());
    }
}
