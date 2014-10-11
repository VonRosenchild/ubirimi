<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\UbirimiController;
use Ubirimi\Agile\Repository\Sprint;
use Ubirimi\Util;


class AgileSprintController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $agileSprints = Sprint::getAllSprintsForClients();
        $selectedOption = 'sprints';

        return $this->render(__DIR__ . '/../../Resources/views/administration/AgileSprint.php', get_defined_vars());
    }
}
