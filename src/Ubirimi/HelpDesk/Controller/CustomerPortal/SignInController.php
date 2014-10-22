<?php

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SignInController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $signInError = null;

        $httpHOST = Util::getHttpHost();
        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettingsByBaseURL($httpHOST);

        $clientId = $clientSettings['id'];

        if ($session->has('user') && Util::getSubdomain() == $session->get('client/company_domain')) {
            return new RedirectResponse($httpHOST . '/helpdesk/customer-portal/dashboard');
        }

        if ($request->request->has('sign_in')) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');

            $userData = $this->getRepository('ubirimi.user.user')->getCustomerByEmailAddressAndClientId($username, $clientId);

            if ($userData['id']) {
                if (UbirimiContainer::get()['password']->check($password, $userData['password'])) {
                    $session->invalidate();
                    UbirimiContainer::get()['warmup']->warmUpCustomer($userData);

                    return new RedirectResponse($httpHOST . '/helpdesk/customer-portal/dashboard');
                } else {
                    $signInError = true;
                }
            } else {
                $signInError = true;
            }

            if ($signInError) {
                return new RedirectResponse('/helpdesk/customer-portal');
            }
        } else if ($request->request->has('create_account')) {
            return new RedirectResponse('/helpdesk/customer-portal/sign-up');
        } else if ($request->request->has('get_password')) {
            return new RedirectResponse('/helpdesk/customer-portal/get-password');
        }
    }
}
