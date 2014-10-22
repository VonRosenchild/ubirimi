<?php

namespace Ubirimi\Yongo\Controller\Issue\Filter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ListSubscriptionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $filterId = $request->get('id');
        $filter = $this->getRepository('yongo.issue.filter')->getById($filterId);

        $subscriptions = $this->getRepository('yongo.issue.filter')->getSubscriptions($filterId);

        return $this->render(__DIR__ . '/../../../Resources/views/filter/ListSubscription.php', get_defined_vars());
    }
}