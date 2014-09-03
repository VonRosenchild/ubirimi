<?php

namespace Ubirimi\HelpDesk\Controller\SLA;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Repository\HelpDesk\SLA;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Repository\User\User;
use Ubirimi\Yongo\Repository\Issue\IssueType;

class AutocompleteGoalController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $key = $request->get('term');
        $projectId = $request->get('project_id');

        $explodeCriteria = array('=', '(', ')');
        for ($i = 0; $i < count($explodeCriteria); $i++) {
            $keyParts = explode('=', $key);
            $key = end($keyParts);
        }

        $standardKeyWords = array(
            'priority',
            'status',
            'resolution',
            'type',
            'assignee',
            'reporter',
            'currentUser()',
            'AND',
            'OR',
            'NOT IN',
            'IN',
            'Unresolved'
        );

        $SLAs = SLA::getByProjectId($projectId);
        while ($SLAs && $SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {
            $standardKeyWords[] = $SLA['name'];
        }

        $statuses = IssueSettings::getAllIssueSettings('status', $session->get('client/id'));
        $priorities = IssueSettings::getAllIssueSettings('priority', $session->get('client/id'));
        $resolutions = IssueSettings::getAllIssueSettings('resolution', $session->get('client/id'));
        $types = IssueType::getAll($session->get('client/id'));
        $users = User::getByClientId($session->get('client/id'));

        while ($types && $type = $types->fetch_array(MYSQLI_ASSOC)) {
            $standardKeyWords[] = $type['name'];
        }
        while ($statuses && $status = $statuses->fetch_array(MYSQLI_ASSOC)) {
            $standardKeyWords[] = $status['name'];
        }
        while ($priorities && $priority = $priorities->fetch_array(MYSQLI_ASSOC)) {
            $standardKeyWords[] = $priority['name'];
        }
        while ($resolutions && $resolution = $resolutions->fetch_array(MYSQLI_ASSOC)) {
            $standardKeyWords[] = $resolution['name'];
        }
        while ($users && $user = $users->fetch_array(MYSQLI_ASSOC)) {
            $standardKeyWords[] = $user['username'];
        }

        // get last word
        $words = explode(' ', $key);
        $lastWord = $words[count($words) - 1];
        $returnValues = array();
        for ($i = 0; $i < count($standardKeyWords); $i++) {
            if (strpos(mb_strtolower($standardKeyWords[$i]), mb_strtolower($lastWord)) !== false) {
                $returnValues[] = $standardKeyWords[$i];
            }
        }

        return new Response(json_encode($returnValues));
    }
}
