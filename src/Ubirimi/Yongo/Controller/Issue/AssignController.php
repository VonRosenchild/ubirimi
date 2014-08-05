<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\HelpDesk\SLA;
use Ubirimi\Repository\Client;

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

        $issueData = Issue::getByParameters(array('issue_id' => $issueId), $loggedInUserId);

        Issue::updateAssignee($session->get('client/id'), $issueId, $session->get('user/id'), $userAssignedId, $comment);

        // update the date_updated field
        Issue::updateById($issueId, array('date_updated' => $currentDate), $currentDate);

        // check SLA data
        if ($issueData['assignee'] != $userAssignedId) {

            $session = UbirimiContainer::get()['session'];
            $clientSettings = Client::getSettings($session->get('client/id'));
            Issue::updateSLAValue($issueData, $clientId, $clientSettings);

            $SLAs = SLA::getByProjectId($issueData['issue_project_id']);
            while ($SLAs && $SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {
                $conditions = explode("#", $SLA['start_condition']);
                for ($i = 0; $i < count($conditions); $i++) {
                    if ('start_assignee_changed' == $conditions[$i]) {
                        Issue::updateSLAStarted($issueId, $SLA['id'], $currentDate);
                    }
                    if ('stop_assignee_changed' == $conditions[$i]) {
                        Issue::updateSLAStopped($issueId, $SLA['id'], $currentDate);
                    }
                }
            }
        }

        return new Response('');
    }
}