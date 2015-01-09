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

namespace Ubirimi\Yongo\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\Email\Email;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Service\UbirimiService;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class IssueEmailService extends UbirimiService
{
    /**
     * @var \Ubirimi\Yongo\Service\WorkflowService;
     */
    private $workflowService;

    public function __construct(SessionInterface $session, WorkflowService $service)
    {
        parent::__construct($session);

        $this->workflowService = $service;
    }

    public function emailIssueNew($issue)
    {
        $project = UbirimiContainer::get()['repository']->get(YongoProject::class)->getById($issue['issue_project_id']);

        if ($this->workflowService->hasEvent($this->session->get('client/id'), $issue['issue_project_id'], $issue['type'])) {
            $smtpSettings = $this->session->get('client/settings/smtp');
            if ($smtpSettings) {

                Email::$smtpSettings = $smtpSettings;

                UbirimiContainer::get()['repository']->get(Email::class)->triggerNewIssueNotification($this->session->get('client/id'), $issue, $project, $this->session->get('user/id'));
            }
        }
    }

    public function emailIssueUpdate($issue, $oldIssueData, $fieldChanges)
    {
        $smtpSettings = $this->session->get('client/settings/smtp');

        Email::$smtpSettings = $smtpSettings;

        UbirimiContainer::get()['repository']->get(Email::class)->triggerIssueUpdatedNotification($this->session->get('client/id'), $oldIssueData, $this->session->get('user/id'), $fieldChanges);

    }

    public function emailIssueDelete($issue, $project, $extraInformation)
    {
        $smtpSettings = $this->session->get('client/settings/smtp');
        if ($smtpSettings) {

            Email::$smtpSettings = $smtpSettings;
            UbirimiContainer::get()['repository']->get(Email::class)->triggerDeleteIssueNotification($this->session->get('client/id'), $issue, $project, $extraInformation);
        }
    }

    public function emailIssueComment($issue, $project, $content)
    {
        if ($this->session->get('client/settings/smtp')) {

            Email::$smtpSettings = $this->session->get('client/settings/smtp');

            // notify people
            $eventId = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($this->session->get('client/id'), IssueEvent::EVENT_ISSUE_COMMENTED_CODE, 'id');
            $users = UbirimiContainer::get()['repository']->get(YongoProject::class)->getUsersForNotification($issue['issue_project_id'], $eventId, $issue, $this->session->get('user/id'));

            while ($users && $userToNotify = $users->fetch_array(MYSQLI_ASSOC)) {

                if ($userToNotify['user_id'] == $this->session->get('user/id') && !$userToNotify['notify_own_changes_flag']) {
                    continue;
                }

                UbirimiContainer::get()['repository']->get(Email::class)->sendEmailNotificationNewComment($issue, $this->session->get('client/id'), $project, $userToNotify, $content, $this->session->get('user'));
            }
        }
    }

    public function emailIssueLink($issueId, $project, $comment)
    {
        $smtpSettings = $this->session->get('client/settings/smtp');
        if ($smtpSettings) {
            Email::$smtpSettings = $smtpSettings;

            $issue = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters(array('issue_id' => $issueId), $this->session->get('user/id'));
            $eventId = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($this->session->get('client/id'), IssueEvent::EVENT_ISSUE_COMMENTED_CODE, 'id');
            $users = UbirimiContainer::get()['repository']->get(YongoProject::class)->getUsersForNotification($issue['issue_project_id'], $eventId, $issue, $this->session->get('user/id'));

            while ($users && $userToNotify = $users->fetch_array(MYSQLI_ASSOC)) {
                if ($userToNotify['user_id'] == $this->session->get('user/id') && $userToNotify['notify_own_changes_flag']) {
                    UbirimiContainer::get()['repository']->get(Email::class)->sendEmailNotificationNewComment($issue, $this->session->get('client/id'), $project, $userToNotify, $comment, $this->session->get('user'));
                }
                else {
                    UbirimiContainer::get()['repository']->get(Email::class)->sendEmailNotificationNewComment($issue, $this->session->get('client/id'), $project, $userToNotify, $comment, $this->session->get('user'));
                }
            }
        }
    }

    public function emailIssueShare($issue, $userIds, $noteContent)
    {
        $smtpSettings = $this->session->get('client/settings/smtp');

        if ($smtpSettings) {

            Email::$smtpSettings = $smtpSettings;
            $userThatShares = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getById($this->session->get('user/id'));
            for ($i = 0; $i < count($userIds); $i++) {

                $user = UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getById($userIds[$i]);

                UbirimiContainer::get()['repository']->get(Email::class)->shareIssue($this->session->get('client/id'), $issue, $userThatShares, $user['email'], $noteContent);
            }
        }
    }

    public function emailIssueWorkLogged($issue, $project, $extraInformation)
    {
        $smtpSettings = $this->session->get('client/settings/smtp');

        if ($smtpSettings) {

            Email::$smtpSettings = $smtpSettings;
            // notify people
            $eventId = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($this->session->get('client/id'), IssueEvent::EVENT_WORK_LOGGED_ON_ISSUE_CODE, 'id');
            $users = UbirimiContainer::get()['repository']->get(YongoProject::class)->getUsersForNotification($issue['issue_project_id'], $eventId, $issue, $this->session->get('user/id'));

            while ($users && $userToNotify = $users->fetch_array(MYSQLI_ASSOC)) {

                if ($userToNotify['user_id'] == $this->session->get('user/id') && !$userToNotify['notify_own_changes_flag']) {
                    continue;
                }

                UbirimiContainer::get()['repository']->get(Email::class)->sendEmailNotificationWorkLogged($issue, $this->session->get('client/id'), $project, $userToNotify, $extraInformation, $this->session->get('user'));
            }
        }
    }

    public function emailIssueAddAttachemnt($issue, $project, $extraInformation)
    {
        $smtpSettings = $this->session->get('client/settings/smtp');

        if ($smtpSettings) {

            Email::$smtpSettings = $smtpSettings;
            // notify people
            $eventId = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($this->session->get('client/id'), IssueEvent::EVENT_ISSUE_UPDATED_CODE, 'id');
            $users = UbirimiContainer::get()['repository']->get(YongoProject::class)->getUsersForNotification($issue['issue_project_id'], $eventId, $issue, $this->session->get('user/id'));

            while ($users && $userToNotify = $users->fetch_array(MYSQLI_ASSOC)) {

                if ($userToNotify['user_id'] == $this->session->get('user/id') && !$userToNotify['notify_own_changes_flag']) {
                    continue;
                }

                UbirimiContainer::get()['repository']->get(Email::class)->sendEmailNotificationAddAttachment($issue, $this->session->get('client/id'), $project, $userToNotify, $extraInformation, $this->session->get('user'));
            }
        }
    }

    public function emailIssueWorkLogUpdated($issue, $project, $extraInformation)
    {
        $smtpSettings = $this->session->get('client/settings/smtp');

        if ($smtpSettings) {

            Email::$smtpSettings = $smtpSettings;
            // notify people
            $eventId = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($this->session->get('client/id'), IssueEvent::EVENT_ISSUE_WORKLOG_UPDATED_CODE, 'id');
            $users = UbirimiContainer::get()['repository']->get(YongoProject::class)->getUsersForNotification($issue['issue_project_id'], $eventId, $issue, $this->session->get('user/id'));

            while ($users && $userToNotify = $users->fetch_array(MYSQLI_ASSOC)) {

                if ($userToNotify['user_id'] == $this->session->get('user/id') && !$userToNotify['notify_own_changes_flag']) {
                    continue;
                }

                UbirimiContainer::get()['repository']->get(Email::class)->sendEmailNotificationWorkLogUpdated($issue, $this->session->get('client/id'), $project, $userToNotify, $extraInformation, $this->session->get('user'));
            }
        }
    }

    public function emailIssueWorkLogDeleted($issue, $project, $extraInformation)
    {
        $smtpSettings = $this->session->get('client/settings/smtp');

        if ($smtpSettings) {

            Email::$smtpSettings = $smtpSettings;
            // notify people
            $eventId = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($this->session->get('client/id'), IssueEvent::EVENT_ISSUE_WORKLOG_DELETED_CODE, 'id');
            $users = UbirimiContainer::get()['repository']->get(YongoProject::class)->getUsersForNotification($issue['issue_project_id'], $eventId, $issue, $this->session->get('user/id'));

            while ($users && $userToNotify = $users->fetch_array(MYSQLI_ASSOC)) {

                if ($userToNotify['user_id'] == $this->session->get('user/id') && !$userToNotify['notify_own_changes_flag']) {
                    continue;
                }

                UbirimiContainer::get()['repository']->get(Email::class)->sendEmailNotificationWorkLogDeleted($issue, $this->session->get('client/id'), $project, $userToNotify, $extraInformation, $this->session->get('user'));
            }
        }
    }
}