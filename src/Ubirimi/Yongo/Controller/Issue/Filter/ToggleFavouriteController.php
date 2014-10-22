<?php

namespace Ubirimi\Yongo\Controller\Issue\Filter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ToggleFavouriteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $filterId = $request->request->get('id');
        $userId = $session->get('user/id');

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository('yongo.issue.filter')->toggleFavourite($userId, $filterId, $currentDate);
    }
}
