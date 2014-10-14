<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ShareController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueId = $_POST['id'];
        $noteContent = $_POST['note'];
        $userIds = $_POST['user_id'];

        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters);

        $issueEvent = new IssueEvent($issue, null, IssueEvent::STATUS_UPDATE, array('userIds' => $userIds, 'noteContent' => $noteContent));
        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_SHARE_EMAIL, $issueEvent);
    }
}
