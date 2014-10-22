<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\Email;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SaveIssueTransitionNoScreenController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {

        Util::checkUserIsLoggedInAndRedirect();

        $clientId = UbirimiContainer::get()['session']->get('client/id');
        $loggedInUserId = UbirimiContainer::get()['session']->get('user/id');

        $workflowStepIdFrom = $request->request->get('step_id_from');
        $workflowStepIdTo = $request->request->get('step_id_to');
        $workflowId = $request->request->get('workflow_id');
        $issueId = $request->request->get('issue_id');

        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);

        $workflowData = $this->getRepository('yongo.workflow.workflow')->getDataByStepIdFromAndStepIdTo($workflowId, $workflowStepIdFrom, $workflowStepIdTo);
        $issue = $this->getRepository('yongo.issue.issue')->getByParameters(array('issue_id' => $issueId), $loggedInUserId);

        $canBeExecuted = $this->getRepository('yongo.workflow.workflow')->checkConditionsByTransitionId($workflowData['id'], $loggedInUserId, $issue);

        if ($canBeExecuted) {

            $smtpSettings = $session->get('client/settings/smtp');
            if ($smtpSettings) {
                Email::$smtpSettings = $smtpSettings;
            }

            $date = Util::getServerCurrentDateTime();
            $this->getRepository('yongo.workflow.workflowFunction')->triggerPostFunctions($clientId, $issue, $workflowData, array(), $loggedInUserId, $date);

            // update the date_updated field
            $this->getRepository('yongo.issue.issue')->updateById($issueId, array('date_updated' => $date), $date);

            return new Response('success');

        } else {
            return new Response('can_not_be_executed');
        }
    }
}