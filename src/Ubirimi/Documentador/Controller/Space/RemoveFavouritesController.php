<?php

namespace Ubirimi\Documentador\Controller\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class RemoveFavouritesController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $loggedInUserId = $session->get('user/id');
        $spaceId = $request->request->get('id');

        $this->getRepository(Space::class)->removeFavourite($spaceId, $loggedInUserId);

        return new Response('');
    }
}