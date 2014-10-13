<?php

namespace Ubirimi\Api\Controller\User;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\User;
use Ubirimi\UbirimiController;

class GetController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        UbirimiContainer::get()['api.auth']->auth($request);

        $username = $request->get('username');

        $user = $this->getRepository('ubirimi.user.user')->getByUsernameAndClientDomain($username, $request->get('api_client_domain'));

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('User [%s] not found', $username));
        }

        return new JsonResponse($user);
    }
}
