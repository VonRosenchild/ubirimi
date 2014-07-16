<?php

namespace Ubirimi\General\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Repository\User\User;
use Ubirimi\Util;
use Ubirimi\Repository\Log;
use Ubirimi\SystemProduct;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->request->get('user_id');
        $user = User::getById($userId);

        User::deleteById($userId);

        // todo: delete the avatar, if any

        $currentDate = Util::getServerCurrentDateTime();
        Log::add(
            $session->get('client/id'),
            SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS,
            $session->get('user/id'),
            'DELETE User ' . $user['username'],
            $currentDate
        );

        return new Response('');
    }
}
