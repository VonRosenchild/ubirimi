<?php

namespace Ubirimi\FrontendCOM\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Paymill\Request as PaymillRequest;
use Paymill\Models\Request\Client as PaymillClient;

class BillingController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $page = 'account_billing';
        $clientId = $session->get('client/id');
        $content = 'account/Billing.php';

        $client = $this->getRepository('ubirimi.general.client')->getById($clientId);

        $emptyCountry = false;
        if ($client['sys_country_id'] == null) {
            $emptyCountry = true;
        } else {
            $currentCardData = null;
            if ($client['paymill_id'] && $client['is_payable']) {
                $paymillClient = new PaymillClient();
                $requestPaymill = new PaymillRequest(UbirimiContainer::get()['paymill.private_key']);

                $paymillClient->setId($client['paymill_id']);
                $response = $requestPaymill->getOne($paymillClient);

                if (count($response->getPayment())) {
                    $currentCardData = $response->getPayment()[0];
                }
            }
        }

        return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
    }
}