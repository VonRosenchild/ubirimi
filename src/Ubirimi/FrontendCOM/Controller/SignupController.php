<?php

namespace Ubirimi\FrontendCOM\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\GeneralTaskQueue;
use Ubirimi\Repository\User\User;
use Ubirimi\UbirimiController;use Ubirimi\Util;

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

        $countries = Util::getCountries();

        $clientCreated = false;
        if (isset($_POST['add_company'])) {
            $company_name = Util::cleanRegularInputField($_POST['company_name']);
            $companyDomain = Util::cleanRegularInputField($_POST['company_domain']);

            $admin_first_name = Util::cleanRegularInputField($_POST['admin_first_name']);
            $admin_last_name = Util::cleanRegularInputField($_POST['admin_last_name']);
            $admin_email = Util::cleanRegularInputField($_POST['admin_email']);
            $adminUsername = $_POST['admin_username'];
            $admin_pass_1 = Util::cleanRegularInputField($_POST['admin_pass_1']);
            $admin_pass_2 = Util::cleanRegularInputField($_POST['admin_pass_2']);

            $agreeTerms = isset($_POST['agree_terms']) ? Util::cleanRegularInputField($_POST['agree_terms']) : false;

            $errorInFormData = false;

            // check data for integrity

            if (empty($company_name))
                $errors['empty_company_name'] = true;

            if (empty($companyDomain))
                $errors['empty_company_domain'] = true;

            if (!$errors['empty_company_domain']) {
                if (!preg_match('/^[a-z]+$/', $companyDomain)) {
                    $errors['company_domain_not_valid'] = true;
                }
                if (!$errors['company_domain_not_valid']) {

                    $domainAvailable = Client::checkAvailableDomain($companyDomain);
                    if (!$domainAvailable)
                        $errors['company_domain_not_unique'] = true;
                }
            }

            if (!Util::validateUsername($adminUsername))
                $errors['invalid_username'] = true;

            if (empty($admin_first_name))
                $errors['empty_admin_first_name'] = true;

            if (empty($admin_last_name))
                $errors['empty_admin_last_name'] = true;

            if (empty($admin_email))
                $errors['empty_admin_email'] = true;

            if (empty($adminUsername))
                $errors['empty_admin_username'] = true;

            if (empty($admin_pass_1))
                $errors['empty_admin_pass_1'] = true;

            if ($admin_pass_1 != $admin_pass_2)
                $errors['passwords_do_not_match'] = true;

            if (!Util::isValidEmail($admin_email))
                $errors['admin_email_not_valid'] = true;

            if (!$agreeTerms)
                $errors['not_agree_terms'] = true;

            $emailResult = User::getByEmailAddressAndIsClientAdministrator(mb_strtolower($admin_email));

            if ($emailResult)
                $errors['admin_email_already_exists'] = true;

            $problemFound = false;
            foreach ($errors as $error)
                if ($error)
                    $problemFound = true;

            if (!$problemFound) {

                // prepare the data
                $currentDate = Util::getCurrentDateTime();
                $baseURL = 'https://' . $companyDomain . '.ubirimi.net';

                /* save data to the general task queue */
                GeneralTaskQueue::savePendingClientData(json_encode(
                    array('companyName' => $company_name,
                        'companyDomain' => $companyDomain,
                        'baseURL' => $baseURL,
                        'adminFirstName' => $admin_first_name,
                        'adminLastName' => $admin_last_name,
                        'adminUsername' => $adminUsername,
                        'adminPass' => $admin_pass_1,
                        'adminEmail' => $admin_email)));

                $session->set('client_account_created', true);
                $_POST = array();

                $clientCreated = true;

                return new RedirectResponse('/sign-up-success');
            }
        }

        $content = 'Signup.php';
        $page = 'signup';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
