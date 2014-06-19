<?php

namespace Ubirimi\FrontendNET\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\User\User;

class SignupController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $session->remove('user_account_created');

        $httpHOST = Util::getHttpHost();
        $clientDomain = Util::getSubdomain();

        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $client = Client::getById($clientId);
        $clientSettings = Client::getSettings($clientId);

        $errors = array('empty_email' => false,
            'email_not_valid' => false,
            'empty_first_name' => false,
            'empty_last_name' => false,
            'email_already_exists' => false,
            'empty_username' => false,
            'empty_password' => false,
            'password_mismatch' => false,
            'duplicate_username' => false,
            'invalid_username' => false);

        if (isset($_POST['cancel'])) {
            return new RedirectResponse('/');
        } else if (isset($_POST['create-user-account'])) {

            $email = Util::cleanRegularInputField($_POST['email']);
            $firstName = Util::cleanRegularInputField($_POST['first_name']);
            $lastName = Util::cleanRegularInputField($_POST['last_name']);
            $username = Util::cleanRegularInputField($_POST['username']);
            $password = Util::cleanRegularInputField($_POST['password']);
            $passwordAgain = Util::cleanRegularInputField($_POST['password_again']);

            if (empty($email)) {
                $errors['empty_email'] = true;
            } else if (!Util::isValidEmail($email)) {
                $errors['email_not_valid'] = true;
            }

            $emailData = User::getUserByClientIdAndEmailAddress($clientId, mb_strtolower($email));

            if (!Util::validateUsername($username)) {
                $errors['invalid_username'] = true;
            } else {
                $userData = User::getByUsernameAndClientId($username, $clientId);
                if ($userData) {
                    $errors['duplicate_username'] = true;
                }
            }

            if ($emailData)
                $errors['email_already_exists'] = true;

            if (empty($firstName))
                $errors['empty_first_name'] = true;

            if (empty($lastName))
                $errors['empty_last_name'] = true;

            if (empty($username))
                $errors['empty_username'] = true;

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
                        'username' => $username,
                        'password' => $password,
                        'clientDomain' => $client['company_domain']
                    )
                );

                $session->set('user_account_created', true);
                $email = $firstName = $lastName = $username = $password = $passwordAgain = $email = null;
            }
        }

        $content = 'Signup.php';

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
