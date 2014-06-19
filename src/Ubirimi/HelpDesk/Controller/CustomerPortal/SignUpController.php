<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\User\User;
    use Ubirimi\Util;

    $httpHOST = Util::getHttpHost();

    $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
    $client = Client::getById($clientId);
    $clientSettings = Client::getSettings($clientId);

    $errors = array('empty_email' => false,
        'email_not_valid' => false,
        'empty_first_name' => false,
        'empty_last_name' => false,
        'email_already_exists' => false,
        'empty_password' => false,
        'password_mismatch' => false);

    if (isset($_POST['create_account'])) {

        $email = Util::cleanRegularInputField($_POST['email_address']);
        $firstName = Util::cleanRegularInputField($_POST['first_name']);
        $lastName = Util::cleanRegularInputField($_POST['last_name']);
        $password = Util::cleanRegularInputField($_POST['password']);
        $passwordAgain = Util::cleanRegularInputField($_POST['password_repeat']);

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

            header('Location: /helpdesk/customer-portal');
        }
    }

    require_once __DIR__ . '/../../Resources/views/customer_portal/SignUp.php';