<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SetDisplayColumnsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $data = $request->request->get('data');

        $this->getRepository(UbirimiUser::class)->updateDisplayColumns($loggedInUserId, $data);
        $session->set('user/issues_display_columns', $data);
    }
}