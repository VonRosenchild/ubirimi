<?php

namespace Ubirimi\Yongo\Controller\Issue\Filter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;

class ToggleFavouriteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();
        $filterId = $request->request->get('id');
        $userId = $session->get('user/id');

        $currentDate = Util::getServerCurrentDateTime();

        $this->getRepository(IssueFilter::class)->toggleFavourite($userId, $filterId, $currentDate);
    }
}
