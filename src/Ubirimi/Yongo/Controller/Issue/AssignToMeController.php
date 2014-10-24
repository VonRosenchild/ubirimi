<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class AssignToMeController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $currentDate = Util::getServerCurrentDateTime();

        $issueId = $request->get('id');

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $issueData = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => $issueId), $loggedInUserId);
        $this->getRepository(Issue::class)->updateAssignee($clientId, $issueId, $loggedInUserId, $loggedInUserId);

        // update the date_updated field
        $this->getRepository(Issue::class)->updateById($issueId, array('date_updated' => $currentDate), $currentDate);

        $userAssigned = $this->getRepository(UbirimiUser::class)->getById($loggedInUserId);
        $newUserAssignedName = $userAssigned['first_name'] . ' ' . $userAssigned['last_name'];
        $oldUserAssignedName = $issueData['ua_first_name'] . ' ' . $issueData['ua_last_name'];

        $project = $this->getRepository(YongoProject::class)->getById($issueData['issue_project_id']);

        $smtpSettings = UbirimiContainer::get()['session']->get('client/settings/smtp');

        Email::$smtpSettings = $smtpSettings;
        Email::triggerAssignIssueNotification(
            $clientId,
            $issueData,
            $oldUserAssignedName,
            $newUserAssignedName,
            $project,
            $loggedInUserId,
            null
        );

        $issueEvent = new IssueEvent(null, null, IssueEvent::STATUS_UPDATE);
        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_UPDATE_ASSIGNEE_EMAIL, $issueEvent);

        return new Response('');
    }
}