<?php

namespace Ubirimi\FrontendNET\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\User;
use Ubirimi\Util;
use Ubirimi\PasswordHash;
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

        if (isset($_POST['sign_in'])) {

            $username = $_POST['username'];
            $password = $_POST['password'];

            $userData = User::getByUsernameAndClientId($username, $clientId);
            if ($userData['id']) {
                $t_hasher = new PasswordHash(8, false);
                $passwordIsOK = $t_hasher->CheckPassword($password, $userData['password']);

                if ($passwordIsOK) {
                    $session->invalidate();
                    $clientId = $userData['client_id'];

                    UbirimiContainer::get()['warmup']->warmUpClient($userData, true, true);

                    $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
                    Log::add($clientId, SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS, $userData['id'], 'LOG IN', $date);

                    return new RedirectResponse($httpHOST . '/yongo/my-dashboard');
                } else $signInError = true;
            } else $signInError = true;
        } else if (isset($_POST['create_account'])) {
            return new RedirectResponse('/sign-up');
        }

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
