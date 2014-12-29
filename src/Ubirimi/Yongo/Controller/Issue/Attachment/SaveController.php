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

namespace Ubirimi\Yongo\Controller\Issue\Attachment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\VarDumper\VarDumper;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Event\IssueEvent;
use Ubirimi\Yongo\Event\YongoEvents;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueComment;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class SaveController extends UbirimiController
{

    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');
        $issueId = $request->request->get('issue_id');
        $attachIdsToBeKept = $request->request->get('attach_ids');
        $comment = Util::cleanRegularInputField($request->request->get('comment'));

        if (!is_array($attachIdsToBeKept)) {
            $attachIdsToBeKept = array();
        }

        Util::manageModalAttachments($issueId, $session->get('user/id'), $attachIdsToBeKept);

        if (!empty($comment)) {
            $currentDate = Util::getServerCurrentDateTime();
            $this->getRepository(IssueComment::class)->add($issueId, $session->get('user/id'), $comment, $currentDate);
        }

        // send email notification
        $issueQueryParameters = array('issue_id' => $issueId);
        $issue = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $loggedInUserId);
        $project = $this->getRepository(YongoProject::class)->getById($issue['issue_project_id']);

        $issueEventData = array('user_id' => $loggedInUserId,
                                'attachmentIds' => UbirimiContainer::get()['session']->get('added_attachments_in_screen'),
                                'comment' => $comment);
        $issueEvent = new IssueEvent($issue, $project, IssueEvent::STATUS_UPDATE, $issueEventData);

        UbirimiContainer::get()['dispatcher']->dispatch(YongoEvents::YONGO_ISSUE_ADD_ATTACHMENT, $issueEvent);

        UbirimiContainer::get()['session']->remove('added_attachments_in_screen');

        return new Response('');
    }
}