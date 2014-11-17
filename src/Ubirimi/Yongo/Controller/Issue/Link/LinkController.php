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

namespace Ubirimi\Yongo\Controller\Issue\Link;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueComment;
use Ubirimi\Yongo\Repository\Issue\IssueLinkType;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class LinkController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $issueId = $request->request->get('id');
        $issue = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => $issueId), $loggedInUserId);
        $project = $this->getRepository(YongoProject::class)->getById($issue['issue_project_id']);

        $linkTypeData = explode('_', $request->request->get('link_type'));
        $linkTypeId = $linkTypeData[0];
        $type = $linkTypeData[1];
        $linkedIssues = $request->request->get('linked_issues');
        $comment = Util::cleanRegularInputField($request->request->get('comment'));

        $date = Util::getServerCurrentDateTime();
        $this->getRepository(IssueLinkType::class)->addLink($issueId, $linkTypeId, $type, $linkedIssues, $date);

        if ($comment != '') {
            $this->getRepository(IssueComment::class)->add($issueId, $loggedInUserId, $comment, $date);

            $issueEvent = new IssueEvent($issue, $project, IssueEvent::STATUS_UPDATE, array('issueId' => $issueId, 'comment' => $comment));
            UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_LINK_EMAIL, $issueEvent);
        }

        return new Response('');
    }
}