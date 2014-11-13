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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class UpdatePreferencesController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $errors = array();

        $userId = $request->request->get('id');
        $issuesPerPage = $request->request->get('issues_per_page');
        $notifyOwnChangesFlag = $request->request->get('notify_own_changes');
        $countryId = $request->request->get('country_id');
        $emailAddress = $request->request->get('email_address');

        if (empty($emailAddress)) {
            $errors['empty_email'] = true;
        } else if (!Util::isValidEmail($emailAddress)) {
            $errors['email_not_valid'] = true;
        }

        $emailData = Util::checkEmailAddressExistenceWithinClient(
            mb_strtolower($emailAddress),
            $userId,
            $session->get('client/id')
        );

        if ($emailData) {
            $errors['email_already_exists'] = true;
        }

        if (0 == count($errors)) {
            $parameters = array(
                array('field' => 'issues_per_page', 'value' => $issuesPerPage, 'type' => 'i'),
                array('field' => 'notify_own_changes_flag', 'value' => $notifyOwnChangesFlag, 'type' => 'i'),
                array('field' => 'country_id', 'value' => $countryId, 'type' => 'i'),
                array('field' => 'email', 'value' => $emailAddress, 'type' => 's')
            );

            $this->getRepository(UbirimiUser::class)->updatePreferences($userId, $parameters);

            $session->set('user/issues_per_page', $issuesPerPage);

        }

        return new JsonResponse($errors);
    }
}
