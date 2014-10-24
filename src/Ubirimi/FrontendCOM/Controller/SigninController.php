<?php

namespace Ubirimi\FrontendCOM\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;

class SigninController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $signInError = null;
        $page = 'sign-in';
        $content = 'Signin.php';

        if ($request->request->has('sign_in')) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');

            $userData = $this->getRepository(UbirimiUser::class)->getByUsernameAndAdministrator($username);
            if ($userData['id']) {
                if (UbirimiContainer::get()['password']->check($password, $userData['password'])) {
                    $httpHOST = $request->server->get('HTTP_HOST');

                    UbirimiContainer::get()['warmup']->warmUpClient($userData);
                    UbirimiContainer::get()['login.time']->clientSaveLoginTime($userData['client_id']);

                    return new RedirectResponse('/account/home');
                } else $signInError = true;
            } else $signInError = true;
        }

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
