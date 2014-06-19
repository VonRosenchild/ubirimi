<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Event\IssueEvent;
    use Ubirimi\Yongo\Event\YongoEvents;
    use Ubirimi\Yongo\Repository\Issue\Issue;

    Util::checkUserIsLoggedInAndRedirect();

    $issueId = $_POST['id'];
    $noteContent = $_POST['note'];
    $userIds = $_POST['user_id'];

    $issueQueryParameters = array('issue_id' => $issueId);
    $issue = Issue::getByParameters($issueQueryParameters);

    $issueEvent = new IssueEvent($issue, null, IssueEvent::STATUS_UPDATE, array('userIds' => $userIds, 'noteContent' => $noteContent));
    UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_SHARE_EMAIL, $issueEvent);