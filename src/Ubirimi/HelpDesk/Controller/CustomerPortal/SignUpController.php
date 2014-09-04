<?php

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\User\User;

class SignUpController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $httpHOST = Util::getHttpHost();

        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $client = Client::getById($clientId);
        $clientSettings = Client::getSettings($clientId);

        $errors = array(
            'empty_email' => false,
            'email_not_valid' => false,
            'empty_first_name' => false,
            'empty_last_name' => false,
            'email_already_exists' => false,
            'empty_password' => false,
            'password_mismatch' => false
        );

        if ($request->request->has('create_account')) {

            $email = Util::cleanRegularInputField($request->request->get('email_address'));
            $firstName = Util::cleanRegularInputField($request->request->get('first_name'));
            $lastName = Util::cleanRegularInputField($request->request->get('last_name'));
            $password = Util::cleanRegularInputField($request->request->get('password'));
            $passwordAgain = Util::cleanRegularInputField($request->request->get('password_repeat'));

            if (empty($email)) {
                $errors['empty_email'] = true;
            } else if (!Util::isValidEmail($email)) {
                $errors['email_not_valid'] = true;
            }

            $emailData = User::getUserByClientIdAndEmailAddress($clientId, mb_strtolower($email));

            if ($emailData)
                $errors['email_already_exists'] = true;

            if (empty($firstName))
                $errors['empty_first_name'] = true;

            if (empty($lastName))
                $errors['empty_last_name'] = true;

            if (empty($password))
                $errors['empty_password'] = true;

            if ($password != $passwordAgain)
                $errors['password_mismatch'] = true;

            if (Util::hasNoErrors($errors)) {

                $userId = UbirimiContainer::get()['user']->newUser(
                    array(
                        'clientId' => $clientId,
                        'firstName' => $firstName,
                        'lastName' => $lastName,
                        'email' => $email,
                        'isCustomer' => true,
                        'password' => $password,
                    )
                );

                return new RedirectResponse('/helpdesk/customer-portal');
            }
        }

        return $this->render(__DIR__ . '/../../Resources/views/customer_portal/SignUp.php', get_defined_vars());
    }
}
