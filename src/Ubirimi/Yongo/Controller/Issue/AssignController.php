<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\User\User;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Yongo\Repository\Project\Project;

class AssignController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');
        $clientId = $session->get('client/id');

        $currentDate = Util::getServerCurrentDateTime();

        $issueId = $_POST['issue_id'];
        $userAssignedId = $_POST['user_assigned_id'];
        $comment = Util::cleanRegularInputField($_POST['comment']);

        $issueData = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters(array('issue_id' => $issueId), $loggedInUserId);
        Issue::updateAssignee($session->get('client/id'), $issueId, $session->get('user/id'), $userAssignedId, $comment);

        // update the date_updated field
        Issue::updateById($issueId, array('date_updated' => $currentDate), $currentDate);

        $userAssigned = User::getById($userAssignedId);
        $newUserAssignedName = $userAssigned['first_name'] . ' ' . $userAssigned['last_name'];
        $oldUserAssignedName = $issueData['ua_first_name'] . ' ' . $issueData['ua_last_name'];

        $project = $this->getRepository('yongo.project.project')->getById($issueData['issue_project_id']);

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