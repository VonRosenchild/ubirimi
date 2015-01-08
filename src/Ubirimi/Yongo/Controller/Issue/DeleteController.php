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

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\LogEvent;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $loggedInUserId = $session->get('user/id');

        Util::checkUserIsLoggedInAndRedirect();
        $issueId = $request->get('issue_id');

        $issue = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => $issueId), $loggedInUserId);
        $project = $this->getRepository(YongoProject::class)->getById($issue['issue_project_id']);

        $loggedInUser = $this->getRepository(UbirimiUser::class)->getById($loggedInUserId);
        $issueEvent = new IssueEvent($issue, $project, IssueEvent::STATUS_DELETE, array('loggedInUser' => $loggedInUser));
        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_EMAIL, $issueEvent);

        $this->getRepository(Issue::class)->deleteById($issueId);

        // also delete the substaks
        $childrenIssues = $this->getRepository(Issue::class)->getByParameters(array('parent_id' => $issueId), $loggedInUserId);
        while ($childrenIssues && $childIssue = $childrenIssues->fetch_array(MYSQLI_ASSOC)) {
            $this->getRepository(Issue::class)->deleteById($childIssue['id']);
        }

        $this->getLogger()->addInfo('DELETE Yongo issue ' . $issue['project_code'] . '-' . $issue['nr'], $this->getLoggerContext());

        if ($session->has('last_search_parameters')) {
            return new Response(json_encode(array(
                'go_to_search' => true,
                'url' => $session->get('last_search_parameters'))
            ));
        }

        return new Response(json_encode(array('go_to_dashboard' => true)));
    }
}