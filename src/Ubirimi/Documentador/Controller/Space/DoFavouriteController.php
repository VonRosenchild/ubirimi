<?php

namespace Ubirimi\Documentador\Controller\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class DoFavouriteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $spaceId = $request->request->get('id');

        $currentDate = Util::getServerCurrentDateTime();
        $this->getRepository('documentador.space.space')->addToFavourites($spaceId, $loggedInUserId, $currentDate);

        return new Response('');
    }
}