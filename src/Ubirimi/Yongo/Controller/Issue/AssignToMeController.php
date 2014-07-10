<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;
use Ubirimi\Yongo\Repository\Issue\Issue;

class AssignToMeController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueId = $request->get('id');

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        Issue::updateAssignee($clientId, $issueId, $loggedInUserId, $loggedInUserId);

        $issueEvent = new IssueEvent(null, null, IssueEvent::STATUS_UPDATE);
        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_UPDATE_ASSIGNEE_EMAIL, $issueEvent);

        return new Response('');
    }
}