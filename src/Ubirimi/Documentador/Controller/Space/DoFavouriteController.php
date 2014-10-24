<?php

namespace Ubirimi\Documentador\Controller\Space;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
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
        $this->getRepository(Space::class)->addToFavourites($spaceId, $loggedInUserId, $currentDate);

        return new Response('');
    }
}