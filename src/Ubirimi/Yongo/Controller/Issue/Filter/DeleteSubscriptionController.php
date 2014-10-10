<?php

namespace Ubirimi\Yongo\Controller\Issue\Filter;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Filter;

class DeleteSubscriptionController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $subscriptionId = $request->get('id');
        $subscription = Filter::getSubscriptionById($subscriptionId);

        Filter::deleteSubscriptionById($subscriptionId);

        $subscriptions = Filter::getSubscriptions($subscription['filter_id']);
        if ($subscriptions) {
            return new RedirectResponse('/yongo/filter/' . $subscription['filter_id'] . '/subscription');
        } else {
            return new RedirectResponse('/yongo/filter/all');
        }
    }
}