<?php

namespace Ubirimi\FrontendCOM\Controller\Administration;

use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class ClientsController extends UbirimiController
{
    public function indexAction()
    {
        Util::checkSuperUserIsLoggedIn();

        $clients = $this->getRepository(UbirimiClient::class)->getAll(array('sort_by' => 'client.date_created', 'sort_order' => 'desc'));

        $selectedOption = 'clients';

        return $this->render(__DIR__ . '/../../Resources/views/administration/Clients.php', get_defined_vars());
    }
}
