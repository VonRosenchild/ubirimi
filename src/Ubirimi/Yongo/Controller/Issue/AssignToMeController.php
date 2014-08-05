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
use Ubirimi\Repository\HelpDesk\SLA;
use Ubirimi\Repository\Client;

class AssignToMeController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $currentDate = Util::getServerCurrentDateTime();

        $issueId = $request->get('id');

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $issueData = Issue::getByParameters(array('issue_id' => $issueId), $loggedInUserId);

        Issue::updateAssignee($clientId, $issueId, $loggedInUserId, $loggedInUserId);

        // update the date_updated field
        Issue::updateById($issueId, array('date_updated' => $currentDate), $currentDate);

        // check SLA data
        if ($issueData['assignee'] != $loggedInUserId) {

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

        $issueEvent = new IssueEvent(null, null, IssueEvent::STATUS_UPDATE);
        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_UPDATE_ASSIGNEE_EMAIL, $issueEvent);

        return new Response('');
    }
}