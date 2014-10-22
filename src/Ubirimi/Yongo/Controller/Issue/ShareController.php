<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;

class ShareController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueId = $request->request->get('id');
        $noteContent = $request->request->get('note');
        $userIds = $request->request->get('user_id');

        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = $this->getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters);

        $issueEvent = new IssueEvent($issue, null, IssueEvent::STATUS_UPDATE, array('userIds' => $userIds, 'noteContent' => $noteContent));
        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_SHARE_EMAIL, $issueEvent);
    }
}
