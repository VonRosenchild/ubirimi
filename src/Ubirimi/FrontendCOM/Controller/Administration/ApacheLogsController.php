<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\UbirimiController;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;

class ApacheLogsController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $selectedOption = 'apache_log';

        ob_start();
        passthru('tail -n 1000 ' . escapeshellarg(UbirimiContainer::get()['apache.error_log']) . ' | tac');
        $output = trim(ob_get_clean());

        $output = str_replace('[:error]', '<span style="background-color: red">[:error]</span>', $output);

        return $this->render(__DIR__ . '/../../Resources/views/administration/ApacheLogs.php', get_defined_vars());
    }
}
