<?php

namespace Ubirimi\FrontendCOM\Controller\Account;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\User\User;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Paymill\Request as PaymillRequest;
use Paymill\Models\Request\Client as PaymillClient;
use Paymill\Models\Request\Subscription as PaymillSubscription;
use Paymill\Models\Request\Payment as PaymillPayment;
use Ubirimi\PaymentUtil;

class BillingUpdateController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $page = 'account_billing_update';
        $clientId = $session->get('client/id');
        $content = 'account/BillingUpdate.php';

        $errors = array();
        $errors['empty_card_number'] = false;
        $errors['card_exp_month'] = false;
        $errors['card_exp_year'] = false;
        $errors['empty_card_name'] = false;
        $errors['empty_card_security'] = false;

        $client = Client::getById($clientId);
        if ($request->request->has('paymillToken')) {

            $cardNumber = Util::cleanRegularInputField($request->request->get('card_number'));
            $cardExpirationMonth = Util::cleanRegularInputField($request->request->get('card_exp_month'));
            $cardExpirationYear = Util::cleanRegularInputField($request->request->get('card_exp_year'));
            $cardName = Util::cleanRegularInputField($request->request->get('card_name'));
            $cardSecurity = Util::cleanRegularInputField($request->request->get('card_security'));

            $paymentUtil = new PaymentUtil();

            $usersClient = Client::getUsers($clientId, null, 'array');
            $numberUsers = count($usersClient);
            $amount = $paymentUtil->getAmountByUsersCount($numberUsers);

            $VAT = 0;
            if (in_array($client['sys_country_id'], array_keys(PaymentUtil::$VATValuePerCountry))) {
                $VAT = $amount * PaymentUtil::$VATValuePerCountry[$client['sys_country_id']] / 100;
            }

            $token = $request->request->get('paymillToken');

            $dateSubscriptionStart = date_create(Util::getServerCurrentDateTime());
            $requestPaymill = new PaymillRequest(UbirimiContainer::get()['paymill.private_key']);

            $client = new PaymillClient();
            $client->setEmail($client['contact_email']);
            $client->setDescription($client['company_name'] . '_' . $client['company_domain']);
            $clientResponse = $requestPaymill->create($client);

            $clientPaymillId = $clientResponse->getId();
            $payment = new PaymillPayment();
            $payment->setClient($clientPaymillId);
            $payment->setToken($token);

            $paymentResponse = $requestPaymill->create($payment);

            $paymentResponse->setExpireMonth($cardExpirationMonth);
            $paymentResponse->setExpireYear($cardExpirationYear);
            $paymentResponse->setCardHolder($cardName);
            $paymentResponse->setCode($cardSecurity);

            $subscription = new PaymillSubscription();
            $subscription->setClient($clientPaymillId);
            $subscription->setAmount(($amount + $VAT) * 100);
            $subscription->setPayment($paymentResponse->getId());
            $subscription->setCurrency('USD');
            $subscription->setInterval('1 month');
            $subscription->setName('Ubirimi product suite for ' . $numberUsers . ' users');
            $subscription->setPeriodOfValidity('20 YEAR');

            $subscriptionResponse = $requestPaymill->create($subscription);

            return new RedirectResponse('/sign-up-success');
        } else {
            $currentCardData = null;
            if ($client['paymill_id'] && $client['is_payable']) {
                $paymillClient = new PaymillClient();
                $requestPaymill = new PaymillRequest(UbirimiContainer::get()['paymill.private_key']);


                $paymillClient->setId($client['paymill_id']);
                $response = $requestPaymill->getOne($paymillClient);
                $currentCardData = $response->getPayment()[0];
            }
            return $this->render(__DIR__ . '/../../Resources/views/_main.php', get_defined_vars());
        }
    }
}