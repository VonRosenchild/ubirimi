<?php

namespace Ubirimi\FrontendCOM\Controller\Account;

use Paymill\Models\Request\Client as PaymillClient;
use Paymill\Models\Request\Payment as PaymillPayment;
use Paymill\Models\Request\Subscription as PaymillSubscription;
use Paymill\Request as PaymillRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\PaymentUtil;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class BillingUpdateDoController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');

        $errors = array();
        $errors['empty_card_number'] = false;
        $errors['card_exp_month'] = false;
        $errors['card_exp_year'] = false;
        $errors['empty_card_name'] = false;
        $errors['empty_card_security'] = false;

        $client = $this->getRepository(UbirimiClient::class)->getById($clientId);
        $paymentUtil = new PaymentUtil();
        $usersClient = $this->getRepository(UbirimiClient::class)->getUsers($clientId, null, 'array');
        $numberUsers = count($usersClient);
        $amount = $paymentUtil->getAmountByUsersCount($numberUsers);
        $VAT = 0;
        if (in_array($client['sys_country_id'], array_keys(PaymentUtil::$VATValuePerCountry))) {
            $VAT = $amount * PaymentUtil::$VATValuePerCountry[$client['sys_country_id']] / 100;
        }

        $totalToBeCharged = $amount + $VAT;

        if ($request->request->has('paymillToken')) {

            $cardNumber = Util::cleanRegularInputField($request->request->get('card_number'));
            $cardExpirationMonth = Util::cleanRegularInputField($request->request->get('card_exp_month'));
            $cardExpirationYear = Util::cleanRegularInputField($request->request->get('card_exp_year'));
            $cardName = Util::cleanRegularInputField($request->request->get('card_name'));
            $cardSecurity = Util::cleanRegularInputField($request->request->get('card_security'));

            $token = $request->request->get('paymillToken');
            $requestPaymill = new PaymillRequest(UbirimiContainer::get()['paymill.private_key']);

            if (!$client['paymill_id']) {

                // if there is no paymill client associated go all the way
                $clientPaymill = new PaymillClient();
                $clientPaymill->setEmail($client['contact_email']);
                $clientPaymill->setDescription($client['company_name'] . '_' . $client['company_domain']);
                $clientResponse = $requestPaymill->create($clientPaymill);

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
                $subscription->setAmount($totalToBeCharged * 100);
                $subscription->setPayment($paymentResponse->getId());
                $subscription->setCurrency('USD');
                $subscription->setInterval('1 month');
                $subscription->setName('Ubirimi product suite for ' . $numberUsers . ' users');
                $subscription->setPeriodOfValidity('20 YEAR');

                $subscriptionResponse = $requestPaymill->create($subscription);

                $this->getRepository(UbirimiClient::class)->updatePaymillId($clientPaymillId, $clientId);
                $session->set('client/paymill_id', $clientPaymillId);

                return new RedirectResponse('/account/billing');
            } else {
                die();
                // only create a payment method
            }
        }
    }
}