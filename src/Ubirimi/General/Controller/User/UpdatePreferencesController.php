<?php

namespace Ubirimi\General\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\User\User;

class UpdatePreferencesController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->request->get('id');
        $issuesPerPage = $request->request->get('issues_per_page');
        $notifyOwnChangesFlag = $request->request->get('notify_own_changes');
        $countryId = $request->request->get('country_id');

        $parameters = array(
            array('field' => 'issues_per_page', 'value' => $issuesPerPage, 'type' => 'i'),
            array('field' => 'notify_own_changes_flag', 'value' => $notifyOwnChangesFlag, 'type' => 'i'),
            array('field' => 'country_id', 'value' => $countryId, 'type' => 'i')
        );

        User::updatePreferences($userId, $parameters);

        $session->set('user/issues_per_page', $issuesPerPage);

        return new Response('');
    }
}
