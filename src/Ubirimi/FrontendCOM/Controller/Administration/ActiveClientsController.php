<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;


use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ActiveClientsController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $clients = $this->getRepository(UbirimiClient::class)->getLastMonthActiveClients();

        $selectedOption = 'active_clients_last_month';

        return $this->render(__DIR__ . '/../../Resources/views/administration/ActiveClients.php', get_defined_vars());
    }
}
