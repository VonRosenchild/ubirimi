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

namespace Ubirimi\General\Controller\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;


class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $session->set('selected_product_id', -1);

        $userId = $request->get('id');
        $location = $request->get('location', 'user_list');
        if ($userId) {
            $user = $this->getRepository(UbirimiUser::class)->getById($userId);
            if ($user['client_id'] != $session->get('client/id')) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }
        }

        $email = $user['email'];
        $firstName = $user['first_name'];
        $lastName = $user['last_name'];
        $username = $user['username'];

        $errors = array('empty_email' => false,
            'email_not_valid' => false,
            'empty_username' => false,
            'invalid_username' => false,
            'duplicate_username' => false,
            'empty_first_name' => false,
            'empty_last_name' => false,
            'email_already_exists' => false,
            'at_least_one_administrator' => false);

        if ($request->request->has('confirm_update_user')) {
            $userId = Util::cleanRegularInputField($request->request->get('user_id'));
            $email = Util::cleanRegularInputField($request->request->get('email'));
            $firstName = Util::cleanRegularInputField($request->request->get('first_name'));
            $lastName = Util::cleanRegularInputField($request->request->get('last_name'));
            $username = Util::cleanRegularInputField($request->request->get('username'));

            $clientAdministrators = $this->getRepository(UbirimiClient::class)->getAdministrators(
                $session->get('client/id'),
                $userId
            );

            $clientAdministratorFlag = 0;
            if ($request->request->has('client_administrator_flag')) {
                $clientAdministratorFlag = Util::cleanRegularInputField(
                    $request->request->get('client_administrator_flag')
                );
            }
            $customerServiceDeskFlag = 0;
            if ($request->request->has('customer_service_desk_flag')) {
                $customerServiceDeskFlag = Util::cleanRegularInputField(
                    $request->request->get('customer_service_desk_flag')
                );
            }

            if (!$clientAdministrators && $clientAdministratorFlag == 0) {
                $errors['at_least_one_administrator'] = true;
            } else if ($clientAdministratorFlag == 0 && $clientAdministrators && $clientAdministrators->num_rows == 0) {
                $errors['at_least_one_administrator'] = true;
            }

            if (empty($email)) {
                $errors['empty_email'] = true;
            } else if (!Util::isValidEmail($email)) {
                $errors['email_not_valid'] = true;
            }

            $emailData = Util::checkEmailAddressExistenceWithinClient(
                mb_strtolower($email),
                $userId,
                $session->get('client/id')
            );

            if ($emailData)
                $errors['email_already_exists'] = true;

            if (empty($firstName))
                $errors['empty_first_name'] = true;

            if (empty($lastName))
                $errors['empty_last_name'] = true;

            if (empty($username))
                $errors['empty_username'] = true;

            if (!Util::validateUsername($username))
                $errors['invalid_username'] = true;
            else {
                $existingUser = $this->getRepository(UbirimiUser::class)->getByUsernameAndClientId(
                    $username,
                    $session->get('client/id'),
                    null,
                    $userId
                );

                if ($existingUser)
                    $errors['duplicate_username'] = true;
            }

            if (Util::hasNoErrors($errors)) {
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository(UbirimiUser::class)->updateById(
                    $userId,
                    $firstName,
                    $lastName,
                    $email,
                    $username,
                    null,
                    $clientAdministratorFlag,
                    $customerServiceDeskFlag,
                    $currentDate
                );

                $userUpdated = $this->getRepository(UbirimiUser::class)->getById($userId);

                $this->getLogger()->addInfo('UPDATE User ' . $userUpdated['username'], $this->getLoggerContext());

                if ($location == 'user_list') {
                    return new RedirectResponse('/general-settings/users');
                }

                return new RedirectResponse('/user/profile/' . $userId);
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / GeneralSettings Settings / Update User';

        $menuSelectedCategory = 'general_user';

        return $this->render(__DIR__ . '/../../Resources/views/user/Edit.php', get_defined_vars());
    }
}
