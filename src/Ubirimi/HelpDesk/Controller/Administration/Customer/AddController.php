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

namespace Ubirimi\HelpDesk\Controller\Administration\Customer;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\HelpDesk\Repository\Organization\Customer;
use Ubirimi\HelpDesk\Repository\Organization\Organization;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $clientDomain = $session->get('client/company_domain');

        $organizationId = $request->query->get('id');

        $errors = array(
            'empty_email' => false,
            'email_not_valid' => false,
            'email_already_exists' => false
        );

        $organizations = $this->getRepository(Organization::class)->getByClientId($session->get('client/id'));

        if ($request->request->has('confirm_new_customer')) {
            $email = Util::cleanRegularInputField($request->request->get('email'));
            $firstName = Util::cleanRegularInputField($request->request->get('first_name'));
            $lastName = Util::cleanRegularInputField($request->request->get('last_name'));

            if (empty($email)) {
                $errors['empty_email'] = true;
            } else if (!Util::isValidEmail($email)) {
                $errors['email_not_valid'] = true;
            }

            $emailData = $this->getRepository(UbirimiUser::class)->getUserByClientIdAndEmailAddress(
                $session->get('client/id'),
                mb_strtolower($email)
            );

            if ($emailData) {
                $errors['email_already_exists'] = true;
            }
            if (Util::hasNoErrors($errors)) {
                $password = Util::randomPassword(8);

                $userId = UbirimiContainer::get()['user']->newUser(array(
                    'clientId' => $session->get('client/id'),
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'password' => $password,
                    'email' => $email,
                    'isCustomer' => true,
                    'clientDomain' => $session->get('client/company_domain')
                ));

                if ($organizationId) {
                    $this->getRepository(Customer::class)->create($organizationId, $userId);
                    return new RedirectResponse('/helpdesk/administration/customers?id=' . $organizationId);
                }

                return new RedirectResponse('/helpdesk/administration/customers');
            }
        }
        $menuSelectedCategory = 'general_user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / GeneralSettings Settings / Create User';

        return $this->render(__DIR__ . '/../../../Resources/views/administration/customer/Add.php', get_defined_vars());
    }
}
