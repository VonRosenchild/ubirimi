<?php

namespace Ubirimi\FrontendCOM\Controller\Account;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\PaymentUtil;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class BillingUpdateController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $page = 'account_billing_update';
        $clientId = $session->get('client/id');
        $content = 'account/BillingUpdate.php';

        $client = $this->getRepository('ubirimi.general.client')->getById($clientId);
        $paymentUtil = new PaymentUtil();
        $usersClient = $this->getRepository('ubirimi.general.client')->getUsers($clientId, null, 'array');
        $numberUsers = count($usersClient);
        $amount = $paymentUtil->getAmountByUsersCount($numberUsers);
        $VAT = 0;
        if (in_array($client['sys_country_id'], array_keys(PaymentUtil::$VATValuePerCountry))) {
            $VAT = $amount * PaymentUtil::$VATValuePerCountry[$client['sys_country_id']] / 100;
        }

        $totalToBeCharged = $amount + $VAT;

        return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
    }
}