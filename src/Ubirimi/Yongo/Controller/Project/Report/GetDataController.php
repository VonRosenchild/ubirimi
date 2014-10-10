<?php

namespace Ubirimi\Yongo\Controller\Project\Report;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Yongo\Repository\Issue\Issue;

class GetDataController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $projectId = $request->get('project_id');
        $statisticType = $request->get('type');
        $loggedInUserId = $session->get('user/id');

        if ($statisticType == 'assignee') {
            $issueQueryParameters = array('project' => array($projectId));
            $issues = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters, $loggedInUserId, null, $loggedInUserId);

            $issuesAssignee = array();
            while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                if (!array_key_exists($issue['assignee'], $issuesAssignee)) {
                    $issuesAssignee[$issue['assignee']] = array('assignee_name' => $issue['ua_first_name'] . ' ' . $issue['ua_last_name'],
                        'issues_count' => 0);
                }
                $issuesAssignee[$issue['assignee']]['issues_count']++;
            }
        }
        return new JsonResponse($issuesAssignee);
    }
}
