<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\HelpDesk\Controller\SLA;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
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

        $SLAs = $this->getRepository(Sla::class)->getByProjectId($projectId);
        while ($SLAs && $SLA = $SLAs->fetch_array(MYSQLI_ASSOC)) {
            $standardKeyWords[] = $SLA['name'];
        }

        $statuses = $this->getRepository(IssueSettings::class)->getAllIssueSettings('status', $session->get('client/id'));
        $priorities = $this->getRepository(IssueSettings::class)->getAllIssueSettings('priority', $session->get('client/id'));
        $resolutions = $this->getRepository(IssueSettings::class)->getAllIssueSettings('resolution', $session->get('client/id'));
        $types = $this->getRepository(IssueType::class)->getAll($session->get('client/id'));
        $users = $this->getRepository(UbirimiUser::class)->getByClientId($session->get('client/id'));

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
