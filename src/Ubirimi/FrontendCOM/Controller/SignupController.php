<?php

namespace Ubirimi\FrontendCOM\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\GeneralTaskQueue;
use Ubirimi\Repository\User\User;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Paymill\Request as PaymillRequest;
use Paymill\Models\Request\Client as PaymillClient;
use Paymill\Models\Request\Subscription as PaymillSubscription;
use Paymill\Models\Request\Payment as PaymillPayment;
use Ubirimi\PaymentUtil;

class SignupController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $session->remove('client_account_created');

        $errors = array();
        $errors['empty_company_name'] = false;
        $errors['empty_company_domain'] = false;
        $errors['company_domain_not_valid'] = false;
        $errors['company_domain_not_unique'] = false;
        $errors['empty_contact_email'] = false;
        $errors['empty_address_1'] = false;
        $errors['empty_city'] = false;
        $errors['empty_district'] = false;
        $errors['empty_country'] = false;
        $errors['empty_admin_email'] = false;
        $errors['empty_admin_username'] = false;
        $errors['admin_username_already_exists'] = false;
        $errors['empty_admin_pass_1'] = false;
        $errors['passwords_do_not_match'] = false;
        $errors['empty_admin_first_name'] = false;
        $errors['empty_admin_last_name'] = false;
        $errors['contact_email_not_valid'] = false;
        $errors['contact_email_already_exists'] = false;
        $errors['admin_email_not_valid'] = false;
        $errors['admin_email_already_exists'] = false;
        $errors['not_agree_terms'] = false;
        $errors['invalid_username'] = false;

        $errors['empty_card_number'] = false;
        $errors['card_exp_month'] = false;
        $errors['card_exp_year'] = false;
        $errors['empty_card_name'] = false;
        $errors['empty_card_security'] = false;

        $countries = Util::getCountries();

        $clientCreated = false;

        if ($request->request->has('paymillToken')) {
            $companyName = Util::cleanRegularInputField($request->request->get('company_name'));
            $companyDomain = Util::cleanRegularInputField($request->request->get('company_domain'));

            $adminFirstName = Util::cleanRegularInputField($request->request->get('admin_first_name'));
            $adminLastName = Util::cleanRegularInputField($request->request->get('admin_last_name'));
            $adminEmailAddress = Util::cleanRegularInputField($request->request->get('admin_email'));
            $adminUsername = $request->request->get('admin_username');
            $admin_pass_1 = Util::cleanRegularInputField($request->request->get('admin_pass_1'));
            $admin_pass_2 = Util::cleanRegularInputField($request->request->get('admin_pass_2'));
            $vatNumber = Util::cleanRegularInputField($request->request->get('vat_number'));
            $country = Util::cleanRegularInputField($request->request->get('country'));

            $cardNumber = Util::cleanRegularInputField($request->request->get('card_number'));
            $cardExpirationMonth = Util::cleanRegularInputField($request->request->get('card_exp_month'));
            $cardExpirationYear = Util::cleanRegularInputField($request->request->get('card_exp_year'));
            $cardName = Util::cleanRegularInputField($request->request->get('card_name'));
            $cardSecurity = Util::cleanRegularInputField($request->request->get('card_security'));

            $numberUsers = Util::cleanRegularInputField($request->request->get('number_users'));

            $agreeTerms = $request->request->has('agree_terms') ?
                Util::cleanRegularInputField($request->request->get('agree_terms')) :
                false;

            $errorInFormData = false;

            // check data for integrity

            if (empty($cardNumber)) {
                $errors['empty_card_number'] = true;
            }

            if (empty($cardExpirationMonth)) {
                $errors['card_exp_month'] = true;
            }

            if (empty($cardExpirationYear)) {
                $errors['card_exp_year'] = true;
            }

            if (empty($cardName)) {
                $errors['empty_card_name'] = true;
            }

            if (empty($cardSecurity)) {
                $errors['empty_card_security'] = true;
            }

            if (empty($companyName)) {
                $errors['empty_company_name'] = true;
            }

            if (empty($companyDomain)) {
                $errors['empty_company_domain'] = true;
            }

            if (!$errors['empty_company_domain']) {
                if (!preg_match('/^[a-z]+$/', $companyDomain)) {
                    $errors['company_domain_not_valid'] = true;
                }
                if (!$errors['company_domain_not_valid']) {

                    $domainAvailable = $this->getRepository('ubirimi.general.client')->checkAvailableDomain($companyDomain);
                    if (!$domainAvailable) {
                        $errors['company_domain_not_unique'] = true;
                    }
                }
            }

            if (!Util::validateUsername($adminUsername)) {
                $errors['invalid_username'] = true;
            }

            if (empty($adminFirstName)) {
                $errors['empty_admin_first_name'] = true;
            }

            if (empty($adminLastName)) {
                $errors['empty_admin_last_name'] = true;
            }

            if (empty($adminEmailAddress)) {
                $errors['empty_admin_email'] = true;
            }

            if (empty($adminUsername)) {
                $errors['empty_admin_username'] = true;
            }

            $usernameResult = $this->getRepository('ubirimi.user.user')->getByEmailAddressAndIsClientAdministrator(mb_strtolower($adminUsername));

            if ($usernameResult) {
                $errors['admin_email_already_exists'] = true;
            }

            if (empty($admin_pass_1)) {
                $errors['empty_admin_pass_1'] = true;
            }

            if ($admin_pass_1 != $admin_pass_2) {
                $errors['passwords_do_not_match'] = true;
            }

            if (!Util::isValidEmail($adminEmailAddress)) {
                $errors['admin_email_not_valid'] = true;
            }

            if (!$agreeTerms) {
                $errors['not_agree_terms'] = true;
            }
            $emailResult = $this->getRepository('ubirimi.user.user')->getByEmailAddressAndIsClientAdministrator(mb_strtolower($adminEmailAddress));

            if ($emailResult) {
                $errors['admin_email_already_exists'] = true;
            }

            $problemFound = false;
            foreach ($errors as $error) {
                if ($error) {
                    $problemFound = true;
                }
            }

            if (!$problemFound) {
                $paymentUtil = new PaymentUtil();

                $amount = $paymentUtil->getAmountByUsersCount($numberUsers);

                $VAT = 0;
                if (in_array($country, array_keys(PaymentUtil::$VATValuePerCountry))) {
                    $VAT = $amount * PaymentUtil::$VATValuePerCountry[$country] / 100;
                }

                $token = $request->request->get('paymillToken');

                $dateSubscriptionStart = date_create(Util::getServerCurrentDateTime());
                date_add($dateSubscriptionStart, date_interval_create_from_date_string('1 months'));

                $requestPaymill = new PaymillRequest(UbirimiContainer::get()['paymill.private_key']);

                $client = new PaymillClient();
                $client->setEmail($adminEmailAddress);
                $client->setDescription($companyName . '_' . $companyDomain . '_' . $adminFirstName . '_' . $adminLastName);
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
                $subscription->setStartAt($dateSubscriptionStart->getTimestamp());

                $subscriptionResponse = $requestPaymill->create($subscription);

                /* save data to the general task queue */
                GeneralTaskQueue::savePendingClientData(json_encode(array(
                    'companyName' => $companyName,
                    'companyDomain' => $companyDomain,
                    'baseURL' => 'https://' . $companyDomain . '.ubirimi.net',
                    'adminFirstName' => $adminFirstName,
                    'adminLastName' => $adminLastName,
                    'adminUsername' => $adminUsername,
                    'adminPass' => $admin_pass_1,
                    'adminEmail' => $adminEmailAddress,
                    'country' => $country,
                    'vatNumber' => $vatNumber,
                    'paymillId' => $clientPaymillId
                )));

                $session->set('client_account_created', true);


                $request->request->replace(array());

                $clientCreated = true;

                return new RedirectResponse('/sign-up-success');
            }
        }

        $content = 'Signup.php';
        $page = 'signup';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}