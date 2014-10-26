<?php

namespace Ubirimi\FrontendNET\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SigninController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $content = 'Signin.php';

        $signInError = null;
        $httpHOST = $_SERVER['SERVER_NAME'];

        $httpHOST = Util::getHttpHost();

        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettingsByBaseURL($httpHOST);
        $clientId = $clientSettings['id'];
        $client = $this->getRepository(UbirimiClient::class)->getById($clientId);
        if ($client['is_payable'] && !$client['paymill_id']) {
            return new RedirectResponse($httpHOST . '/setup-payment');
        }
        if ($session->has('user') && Util::getSubdomain() == $session->get('client/company_domain')) {
            return new RedirectResponse($httpHOST . '/yongo/my-dashboard');
        }

        $context = $request->get('context');

        if ($request->request->has('sign_in')) {

            $username = $request->request->get('username');
            $password = $request->request->get('password');

            $userData = $this->getRepository(UbirimiUser::class)->getByUsernameAndClientId($username, $clientId);
            if ($userData['id']) {
                if (UbirimiContainer::get()['password']->check($password, $userData['password'])) {
                    $session->invalidate();
                    $clientId = $userData['client_id'];

                    UbirimiContainer::get()['warmup']->warmUpClient($userData, true, true);
                    UbirimiContainer::get()['login.time']->userSaveLoginTime($userData['id']);

                    $date = Util::getServerCurrentDateTime();
                    $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS, $userData['id'], 'LOG IN', $date);

                    if ($context) {
                        return new RedirectResponse($httpHOST . $context);
                    } else {
                        return new RedirectResponse($httpHOST . '/yongo/my-dashboard');
                    }

                } else {
                    $signInError = true;
                }
            } else {
                $signInError = true;
            }
        } else if ($request->request->has('create_account')) {
            return new RedirectResponse('/sign-up');
        }

        return $this->render(__DIR__ . '/../Resources/views/_main.php', get_defined_vars());
    }
}
