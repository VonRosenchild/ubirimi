<?php
use Ubirimi\PasswordHash;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\User\User;
use Ubirimi\Util;
use Ubirimi\Container\UbirimiContainer;

$signInError = null;

$httpHOST = Util::getHttpHost();
$clientSettings = Client::getSettingsByBaseURL($httpHOST);

$clientId = $clientSettings['id'];

if ($session->has('user') && Util::getSubdomain() == $session->get('client/company_domain')) {
    header('Location: ' . $httpHOST . '/helpdesk/customer-portal/dashboard');
    die();
}

if (isset($_POST['sign_in'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $userData = User::getCustomerByEmailAddressAndClientId($username, $clientId);

    if ($userData['id']) {

        $t_hasher = new PasswordHash(8, false);
        $passwordIsOK = $t_hasher->CheckPassword($password, $userData['password']);
        if ($passwordIsOK) {

            $session->invalidate();

            UbirimiContainer::get()['warmup']->warmUpCustomer($userData);

            header('Location: ' . $httpHOST . '/helpdesk/customer-portal/dashboard');

            exit;
        } else $signInError = true;


    } else {
        $signInError = true;
    }
    if ($signInError) {
        header('Location: /helpdesk/customer-portal');
    }
} else if (isset($_POST['create_account'])) {
    header('Location: /helpdesk/customer-portal/sign-up');
} else if (isset($_POST['get_password'])) {
    header('Location: /helpdesk/customer-portal/get-password');
}