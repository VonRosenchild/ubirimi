<?php

namespace Ubirimi\FrontendNET\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\User;
use Ubirimi\Util;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;

class SigninController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $content = 'Signin.php';

        $signInError = null;
        $httpHOST = $_SERVER['SERVER_NAME'];

        $httpHOST = Util::getHttpHost();

        $clientSettings = Client::getSettingsByBaseURL($httpHOST);
        $clientId = $clientSettings['id'];

        if ($session->has('user') && Util::getSubdomain() == $session->get('client/company_domain')) {
            return new RedirectResponse($httpHOST . '/yongo/my-dashboard');
        }

        $context = isset($_GET['context']) ? $_GET['context'] : null;

        if (isset($_POST['sign_in'])) {

            $username = $_POST['username'];
            $password = $_POST['password'];

            $userData = User::getByUsernameAndClientId($username, $clientId);
            if ($userData['id']) {
                if (UbirimiContainer::get()['password']->check($password, $userData['password'])) {
                    $session->invalidate();
                    $clientId = $userData['client_id'];

                    UbirimiContainer::get()['warmup']->warmUpClient($userData, true, true);
                    UbirimiContainer::get()['login.time']->userSaveLoginTime($userData['id']);

                    $date = Util::getServerCurrentDateTime();
                    Log::add($clientId, SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS, $userData['id'], 'LOG IN', $date);

                    if ($context) {
                        return new RedirectResponse($httpHOST . $context);
                    } else {
                        return new RedirectResponse($httpHOST . '/yongo/my-dashboard');
                    }

                } else $signInError = true;
            } else $signInError = true;
        } else if (isset($_POST['create_account'])) {
            return new RedirectResponse('/sign-up');
        }

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
