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
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\UbirimiGroup;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $session->set('selected_product_id', -1);

        $clientDomain = $session->get('client/company_domain');

        $groupDevelopers = $this->getRepository(UbirimiGroup::class)->getByName($session->get('client/id'), 'Developers');

        $errors = array(
            'empty_email' => false,
            'email_not_valid' => false,
            'empty_first_name' => false,
            'empty_last_name' => false,
            'email_already_exists' => false,
            'empty_username' => false,
            'empty_password' => false,
            'password_mismatch' => false,
            'invalid_username' => false,
            'duplicate_username' => false
        );

        $svnRepoId = $request->query->get('fsvn');

        if ($svnRepoId) {
            $svnRepo = $this->getRepository(SvnRepository::class)->getById($svnRepoId);
            if ($svnRepo['client_id'] != $session->get('client/id')) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }
        }

        if ($request->request->has('confirm_new_user')) {
            $email = Util::cleanRegularInputField($request->request->get('email'));
            $firstName = Util::cleanRegularInputField($request->request->get('first_name'));
            $lastName = Util::cleanRegularInputField($request->request->get('last_name'));
            $username = Util::cleanRegularInputField($request->request->get('username'));
            $password = Util::cleanRegularInputField($request->request->get('password'));
            $passwordAgain = Util::cleanRegularInputField($request->request->get('password_again'));
            $svnRepoId = Util::cleanRegularInputField($request->request->get('fsvn'));

            if (empty($email)) {
                $errors['empty_email'] = true;
            } else if (!Util::isValidEmail($email)) {
                $errors['email_not_valid'] = true;
            }
            if (!Util::validateUsername($username)) {
                $errors['invalid_username'] = true;
            } else {
                $existingUser = $this->getRepository(UbirimiUser::class)->getByUsernameAndClientId($username, $session->get('client/id'));

                if ($existingUser) {
                    $errors['duplicate_username'] = true;
                }
            }

            $emailData = $this->getRepository(UbirimiUser::class)->getUserByClientIdAndEmailAddress($session->get('client/id'), mb_strtolower($email));
            if ($emailData) {
                $errors['email_already_exists'] = true;
            }
            if (empty($firstName)) {
                $errors['empty_first_name'] = true;
            }
            if (empty($lastName)) {
                $errors['empty_last_name'] = true;
            }
            if (empty($username)) {
                $errors['empty_username'] = true;
            }
            if (empty($password)) {
                $errors['empty_password'] = true;
            }
            if ($password != $passwordAgain) {
                $errors['password_mismatch'] = true;
            }
            if (Util::hasNoErrors($errors)) {
                $serviceData = array(
                    'clientId' => $session->get('client/id'),
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'email' => $email,
                    'username' => $username,
                    'password' => $password,
                    'clientDomain' => $session->get('client/company_domain')
                );

                if ($svnRepoId) {
                    $serviceData['svnRepoId'] = $svnRepoId;
                    $serviceData['repositoryName'] = $svnRepo['name'];
                }

                UbirimiContainer::get()['user']->newUser($serviceData);

                if (!empty($svnRepoId)) {
                    return new RedirectResponse('/svn-hosting/administration/repository/users/' . $svnRepoId);
                }

                return new RedirectResponse('/general-settings/users');
            }
        }
        $menuSelectedCategory = 'general_user';

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / GeneralSettings Settings / Create User';

        return $this->render(__DIR__ . '/../../Resources/views/user/Add.php', get_defined_vars());
    }
}
