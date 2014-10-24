<?php

namespace Ubirimi\Yongo\Controller\Issue\Filter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;

class ListSubscriptionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $filterId = $request->get('id');
        $filter = $this->getRepository(IssueFilter::class)->getById($filterId);

        $subscriptions = $this->getRepository(IssueFilter::class)->getSubscriptions($filterId);

        return $this->render(__DIR__ . '/../../../Resources/views/filter/ListSubscription.php', get_defined_vars());
    }
}