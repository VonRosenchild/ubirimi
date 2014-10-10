<?php

namespace Ubirimi\Yongo\Controller\Issue\Filter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Filter;

class ListSubscriptionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $filterId = $request->get('id');
        $filter = Filter::getById($filterId);

        $subscriptions = Filter::getSubscriptions($filterId);

        return $this->render(__DIR__ . '/../../../Resources/views/filter/ListSubscription.php', get_defined_vars());
    }
}