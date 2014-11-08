<?php

namespace Ubirimi\General\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ChangePasswordController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userId = $request->request->get('user_id');
        $currentPassword = $request->request->get('current_password');
        $newPassword = $request->request->get('new_password');
        $confirmPassword = $request->request->get('confirm_password');

        $user = $this->getRepository(UbirimiUser::class)->getById($userId);

        if (!UbirimiContainer::get()['password']->check($currentPassword, $user['password'])) {
            return new Response('current_password_wrong');
        } else if ($newPassword != $confirmPassword) {
            return new Response('password_mismatch');
        } else if (strlen($newPassword) <= 3 || strlen($confirmPassword) <= 3) {
            return new Response('password_too_short');
        }

        $hash = UbirimiContainer::get()['password']->hash($newPassword);
        $this->getRepository(UbirimiUser::class)->updatePassword($userId, $hash);
        return new Response('ok');
    }
}
