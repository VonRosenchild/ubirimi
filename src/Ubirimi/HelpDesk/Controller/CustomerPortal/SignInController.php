<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\HelpDesk\Controller\CustomerPortal;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SignInController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $signInError = null;

        $httpHOST = Util::getHttpHost();
        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettingsByBaseURL($httpHOST);

        $clientId = $clientSettings['id'];

        if ($session->has('user') && Util::getSubdomain() == $session->get('client/company_domain')) {
            return new RedirectResponse($httpHOST . '/helpdesk/customer-portal/dashboard');
        }

        if ($request->request->has('sign_in')) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');

            $userData = $this->getRepository(UbirimiUser::class)->getCustomerByEmailAddressAndClientId($username, $clientId);

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
