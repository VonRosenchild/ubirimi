<?php

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class IndexController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $signInError = null;

        $httpHOST = Util::getHttpHost();

        $clientSettings = Client::getSettingsByBaseURL($httpHOST);
        $clientId = $clientSettings['id'];

        $client = Client::getById($clientId);

        $sectionPageTitle = $client['company_name'] . ' - Welcome to Customer Portal';

        return $this->render(__DIR__ . '/../../Resources/views/customer_portal/Index.php', get_defined_vars());
    }
}
