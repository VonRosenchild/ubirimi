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
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class AssignController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');
        $clientId = $session->get('client/id');

        $currentDate = Util::getServerCurrentDateTime();

        $issueId = $request->request->get('issue_id');
        $userAssignedId = $request->request->get('user_assigned_id');
        $comment = Util::cleanRegularInputField($request->request->get('comment'));

        $issueData = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => $issueId), $loggedInUserId);
        $this->getRepository(Issue::class)->updateAssignee($session->get('client/id'), $issueId, $session->get('user/id'), $userAssignedId, $comment);

        // update the date_updated field
        $this->getRepository(Issue::class)->updateById($issueId, array('date_updated' => $currentDate), $currentDate);

        $userAssigned = $this->getRepository(UbirimiUser::class)->getById($userAssignedId);
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
            $comment
        );

        return new Response('');
    }
}