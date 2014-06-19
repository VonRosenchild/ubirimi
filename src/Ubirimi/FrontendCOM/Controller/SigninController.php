<?php

namespace Ubirimi\FrontendCOM\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Ubirimi\PasswordHash;
use Ubirimi\Repository\User\User;
use Ubirimi\Container\UbirimiContainer;use Ubirimi\UbirimiController;

class SigninController extends UbirimiController
{
    public function indexAction()
    {
        $signInError = null;
        $page = 'sign-in';
        $content = 'Signin.php';

        if (isset($_POST['sign_in'])) {

            $username = $_POST['username'];
            $password = $_POST['password'];

            $userData = User::getUserByUsernameAndAdministrator($username);
            if ($userData['id']) {
                $hasher = new PasswordHash(8, false);
                $check = $hasher->CheckPassword($password, $userData['password']);
                if ($check) {
                    $httpHOST = $_SERVER['HTTP_HOST'];

                    UbirimiContainer::get()['warmup']->warmUpClient($userData);

                    return new RedirectResponse('/account/home');

                } else $signInError = true;
            } else $signInError = true;
        }

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}



