<?php

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
