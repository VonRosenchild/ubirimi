<?php

namespace Ubirimi\Repository\Email;

use Exception;
use Swift_Mailer;
use Swift_Message;
use Swift_SendmailTransport;
use Swift_SmtpTransport;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueComponent;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueVersion;
use Ubirimi\Yongo\Repository\Issue\IssueWatcher;
use Ubirimi\Yongo\Repository\Project\Project;

class Email {

    public static $smtpSettings;

    public static function sendNewsletter($toEmailAddress, $content, $subject) {
        $emailContent = Email::getEmailHeader();
        $emailContent .= '<br />';
        $emailContent .= '<br />';

        $emailContent .= '<div style="color: #333333; font: 17px Trebuchet MS, sans-serif; white-space: wrap; padding-top: 5px;text-align: left;padding-left: 2px;">' . $content . '</div>';

        $emailContent .= Email::getEmailFooter();

        if (strpos($_SERVER['HTTP_HOST'], 'localhost') === false) {
            $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
            $mailer = Swift_Mailer::newInstance($transport);
            $message = Swift_Message::newInstance($subject)
                ->setFrom(array('flavius@ubirimi.com'))
                ->setTo(array($toEmailAddress))
                ->setBody($emailContent, 'text/html');

            $mailer->send($message);
        }
    }

    public static function sendNewUserNotificationEmail($clientId, $firstName, $lastName, $username, $password, $email, $clientDomain) {
        $subject = Email::$smtpSettings['email_prefix'] . ' ' . 'Ubirimi - A new account has been created for you';

        EmailQueue::add($clientId,
                        Email::$smtpSettings['from_address'],
                        $email,
                        null,
                        $subject,
                        Util::getTemplate('_newUser.php', array(
                            'firstName' => $firstName,
                            'lastName' => $lastName,
                            'username' => $username,
                            'password' => $password,
                            'clientDomain' => $clientDomain)
                        ),
                        Util::getServerCurrentDateTime());
    }

    public static function sendNewCustomerNotificationEmail($clientId, $firstName, $lastName, $email, $password, $clientDomain) {
        $subject = Email::$smtpSettings['email_prefix'] . ' ' . 'Ubirimi - A new customer account has been created for you';

        EmailQueue::add($clientId,
                        Email::$smtpSettings['from_address'],
                        $email,
                        null,
                        $subject,
                        Util::getTemplate('_newUser.php', array(
                            'firstName' => $firstName,
                            'lastName' => $lastName,
                            'email' => $email,
                            'password' => $password,
                            'isCustomer' => true,
                            'clientDomain' => $clientDomain)
                        ),
                        Util::getServerCurrentDateTime());
    }

    public static function sendNewUserRepositoryNotificationEmail($clientId, $firstName, $lastName, $username, $password, $email, $repositoryName) {
        EmailQueue::add($clientId,
                        Email::$smtpSettings['from_address'],
                        $email,
                        null,
                        Email::$smtpSettings['email_prefix'] . ' ' . 'Ubirimi - You have been granted access to ' . $repositoryName . ' SVN Repository',
                        Util::getTemplate('_newRepositoryUser.php',array('first_name' => $firstName,
                                                                                  'last_name' => $lastName,
                                                                                  'username' => $username,
                                                                                  'password' => $password,
                                                                                  'repoName' => $repositoryName,
                                                                                  'clientData' => UbirimiContainer::get()['session']->get('client'))),
                        Util::getServerCurrentDateTime());
    }

    public static function sendUserChangedPasswordForRepositoryNotificationEmail($clientId, $firstName, $lastName, $username, $password, $email, $repositoryName) {
        EmailQueue::add($clientId,
                        Email::$smtpSettings['from_address'],
                        $email,
                        null,
                        Email::$smtpSettings['email_prefix'] . ' ' . 'Ubirimi - Password change for ' . $repositoryName . ' SVN Repository',
                        Util::getTemplate('_userChangePassword.php', array('first_name' => $firstName,
                                                                                 'last_name' => $lastName,
                                                                                 'username' => $username,
                                                                                 'password' => $password,
                                                                                 'repoName' => $repositoryName,
                                                                                 'clientData' => UbirimiContainer::get()['session']->get('client'))),
                        Util::getServerCurrentDateTime());
    }

    public static function triggerNewIssueNotification($clientId, $issue, $project, $loggedInUserId) {

        $eventCreatedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_CREATED_CODE, 'id');
        $users = Project::getUsersForNotification($project['id'], $eventCreatedId, $issue, $loggedInUserId);

        if (!$users) {
            return;
        }

        $usersSentNotification = array();

        while ($user = $users->fetch_array(MYSQLI_ASSOC)) {
            if ($user['user_id'] == $loggedInUserId) {
                if ($user['notify_own_changes_flag']) {
                    Email::sendEmailNewIssue($clientId, $issue, $user);
                    $usersSentNotification[] = $user['user_id'];
                }
            } else {
                Email::sendEmailNewIssue($clientId, $issue, $user);
                $usersSentNotification[] = $user['user_id'];
            }
        }
    }

    public static function triggerAssignIssueNotification($clientId, $issue, $oldUserAssignedName, $newUserAssignedName, $project, $loggedInUserId, $comment) {

        $eventAssignedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_ASSIGNED_CODE, 'id');
        $projectId = $project['id'];
        $users = Project::getUsersForNotification($projectId, $eventAssignedId, $issue, $loggedInUserId);
        if (!$users) {
            return;
        }

        $usersSentNotification = array();
        while ($user = $users->fetch_array(MYSQLI_ASSOC)) {
            if ($user['user_id'] == $loggedInUserId) {
                if ($user['notify_own_changes_flag']) {
                    Email::sendEmailIssueAssign($issue, $clientId, $oldUserAssignedName, $newUserAssignedName, $user, $comment);
                    $usersSentNotification[] = $user['user_id'];
                }
            } else {
                Email::sendEmailIssueAssign($issue, $clientId, $oldUserAssignedName, $newUserAssignedName, $user, $comment);
                $usersSentNotification[] = $user['user_id'];
            }
        }

        // get the issue watchers and send them an email
        $watchers = IssueWatcher::getByIssueId($issue['id']);
        while ($watchers && $watcher = $watchers->fetch_array(MYSQLI_ASSOC)) {
            if (!in_array($watcher['id'], $usersSentNotification)) {
                Email::sendEmailIssueAssign($issue, $clientId, $oldUserAssignedName, $newUserAssignedName, $watcher, $comment);
            }
        }
    }

    private static function sendEmailNewIssue($clientId, $issue, $userToNotify) {
        $versions_affected = IssueVersion::getByIssueIdAndProjectId($issue['id'], $issue['issue_project_id'], Issue::ISSUE_AFFECTED_VERSION_FLAG);
        $versions_fixed = IssueVersion::getByIssueIdAndProjectId($issue['id'], $issue['issue_project_id'], Issue::ISSUE_FIX_VERSION_FLAG);
        $components = IssueComponent::getByIssueIdAndProjectId($issue['id'], $issue['issue_project_id']);
        $client_domain = Util::getSubdomain();

        $subject = Email::$smtpSettings['email_prefix'] . ' ' .
                            "[Issue] - New issue CREATED " .
                            $issue['project_code'] . '-' .
                            $issue['nr'];

        EmailQueue::add($clientId,
                        Email::$smtpSettings['from_address'],
                        $userToNotify['email'],
                        null,
                        $subject,
                        Util::getTemplate('_newIssue.php', array(
                            'issue' => $issue,
                            'client_domain' => $client_domain,
                            'components' => $components,
                            'versions_fixed' => $versions_fixed,
                            'versions_affected' => $versions_affected)
                        ),
                        Util::getServerCurrentDateTime());
    }

    public static function getMailer($smtpSettings) {
        $smtpSecurity = null;
        if ($smtpSettings['smtp_protocol'] == SMTPServer::PROTOCOL_SECURE_SMTP)
            $smtpSecurity = 'ssl';

        if (isset($smtpSettings['tls_flag']))
            $smtpSecurity = 'tls';

        $transport = Swift_SmtpTransport::newInstance($smtpSettings['hostname'], $smtpSettings['port'], $smtpSecurity)
                            ->setUsername($smtpSettings['username'])
                            ->setPassword($smtpSettings['password']);

        return Swift_Mailer::newInstance($transport);
    }

    /* @TODO: remove when email refactoring has been done */
    private static function getEmailHeader($product = null) {
        $text = '<div style="background-color: #F6F6F6; padding: 10px; margin: 10px; width: 720px;">';
        $text .= '<div style="color: #333333;font: 17px Trebuchet MS, sans-serif;white-space: nowrap;padding-bottom: 5px;padding-top: 5px;text-align: left;padding-left: 2px;">';

        $text .= '<a href="https://www.ubirimi.com"><img src="https://www.ubirimi.com/img/email-logo-yongo.png" border="0" /></a>';
        $text .= '<div><img src="https://www.ubirimi.com/img/bg.page.png" /></div>';
        $text .= '</div>';

        return $text;
    }

    private static function getEmailFooter() {
        return '</div>';
    }

    public static function sendEmailIssueAssign($issue, $clientId, $oldUserAssignedName, $newUserAssignedName, $user, $comment) {
        if (Email::$smtpSettings) {
            $subject = Email::$smtpSettings['email_prefix'] . ' ' .
                "[Issue] - Issue UPDATED " .
                $issue['project_code'] . '-' .
                $issue['nr'];

            $date = Util::getServerCurrentDateTime();

            EmailQueue::add($clientId,
                Email::$smtpSettings['from_address'],
                $user['email'],
                null,
                $subject,
                Util::getTemplate('_issueAssign.php', array(
                        'clientDomain' => Util::getSubdomain(),
                        'issue' => $issue,
                        'comment' => $comment,
                        'oldUserAssignedName' => $oldUserAssignedName,
                        'newUserAssignedName' => $newUserAssignedName)
                ),
                $date);
        }
    }

    public static function sendEmailIssueChanged($issue, $clientId, $fieldChanges, $userToNotify) {
        if (Email::$smtpSettings) {
            EmailQueue::add($clientId,
                Email::$smtpSettings['from_address'],
                $userToNotify['email'],
                null,
                Email::$smtpSettings['email_prefix'] . ' ' . "[Issue] - Issue UPDATED " . $issue['project_code'] . '-' . $issue['nr'],
                Util::getTemplate('_issueUpdated.php', array(
                        'clientDomain' => Util::getSubdomain(),
                        'issue' => $issue,
                        'fieldChanges' => $fieldChanges)
                ),
                Util::getServerCurrentDateTime());
        }
    }

    public static function triggerIssueUpdatedNotification($clientId, $issue, $loggedInUserId, $changedFields) {

        $projectId = $issue['issue_project_id'];
        $eventUpdatedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_UPDATED_CODE, 'id');
        $users = Project::getUsersForNotification($projectId, $eventUpdatedId, $issue, $loggedInUserId);

        if (!$users) {
            return;
        }

        $usersSentNotification = array();

        while ($user = $users->fetch_array(MYSQLI_ASSOC)) {
            if ($user['user_id'] == $loggedInUserId) {
                if ($user['notify_own_changes_flag']) {
                    Email::sendEmailIssueChanged($issue, $clientId, $changedFields, $user);
                    $usersSentNotification[] = $user['user_id'];
                }
            } else {
                Email::sendEmailIssueChanged($issue, $clientId, $changedFields, $user);
                $usersSentNotification[] = $user['user_id'];
            }
        }

        // get the issue watchers and send them an email
        $watchers = IssueWatcher::getByIssueId($issue['id']);
        while ($watchers && $watcher = $watchers->fetch_array(MYSQLI_ASSOC)) {
            if (!in_array($watcher['id'], $usersSentNotification)) {
                Email::sendEmailIssueChanged($issue, $clientId, $changedFields, $watcher);
            }
        }
    }

    public static function sendContactMessage($to_address, $name, $subject, $message, $email) {
        $mailer = Util::getUbirmiMailer('contact');

        $message = Swift_Message::newInstance('Contact message - Ubirimi.com')
                            ->setFrom(array('contact@ubirimi.com'))
                            ->setTo($to_address)
                            ->setBody(
                                Util::getTemplate('_contact.php', array(
                                    'name' => $name,
                                    'email' => $email,
                                    'message' => $message,
                                    'subject' => $subject)),
                                'text/html'
                            );

        try {
            $mailer->send($message);
        } catch (Exception $e) {

        }
    }

    public static function sendEmailNotificationNewComment($issue, $clientId, $project, $userToNotify, $content, $user) {
        if (Email::$smtpSettings) {
            $subject = Email::$smtpSettings['email_prefix'] . ' ' . "[Issue] - Issue COMMENT " . $issue['project_code'] . '-' . $issue['nr'];

            $date = Util::getServerCurrentDateTime();

            EmailQueue::add($clientId,
                Email::$smtpSettings['from_address'],
                $userToNotify['email'],
                null,
                $subject,
                Util::getTemplate('_newComment.php',array(
                        'clientDomain' => Util::getSubdomain(),
                        'issue' => $issue,
                        'project' => $project,
                        'content' => $content,
                        'user' => $user)
                ),
                $date);
        }
    }

    public static function sendEmailRetrievePassword($address, $password) {
        $tpl = UbirimiContainer::get()['savant'];
        $tpl->assign(array('password' => $password));

        $message = Swift_Message::newInstance('Restore password - Ubirimi.com')
                        ->setFrom(array('support@ubirimi.com'))
                        ->setTo(array($address))
                        ->setBody($tpl->fetch('_restorePassword.php'), 'text/html');

        $mailer = Util::getUbirmiMailer();

        try {
            $mailer->send($message);
        } catch (Exception $e) {

        }
    }

    private static function sendEmailDeleteIssue($issue, $clientId, $user) {
        if (Email::$smtpSettings) {
            $subject = Email::$smtpSettings['email_prefix'] . ' ' .
                "[Issue] - Issue DELETED " .
                $issue['project_code'] . '-' .
                $issue['nr'];

            EmailQueue::add($clientId,
                Email::$smtpSettings['from_address'],
                $user['email'],
                null,
                $subject,
                Util::getTemplate('_deleteIssue.php', array('issue' => $issue)),
                Util::getServerCurrentDateTime());
        }
    }

    public static function triggerDeleteIssueNotification($clientId, $issue, $loggedInUserId) {
        $projectId = $issue['issue_project_id'];
        $eventDeletedId = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_DELETED_CODE, 'id');
        $users = Project::getUsersForNotification($projectId, $eventDeletedId, $issue, $loggedInUserId);

        if (!$users) {
            return;
        }
        $usersSentNotification = array();

        while ($user = $users->fetch_array(MYSQLI_ASSOC)) {
            if ($user['user_id'] == $loggedInUserId) {
                if ($user['notify_own_changes_flag']) {
                    Email::sendEmailDeleteIssue($issue, $clientId, $user);
                    $usersSentNotification[] = $user['user_id'];
                }
            } else {
                Email::sendEmailDeleteIssue($issue, $clientId, $user);
                $usersSentNotification[] = $user['user_id'];
            }
        }

        // get the issue watchers and send them an email
        $watchers = IssueWatcher::getByIssueId($issue['id']);
        while ($watchers && $watcher = $watchers->fetch_array(MYSQLI_ASSOC)) {
            if (!in_array($watcher['id'], $usersSentNotification)) {
                Email::sendEmailDeleteIssue($issue, $clientId, $watcher);
            }
        }
    }

    public static function sendFeedback($userData, $like, $improve, $newFeatures, $experience) {

        $text = Email::getEmailHeader();
        $text .= '<div style="color: #333333; font: 17px Trebuchet MS, sans-serif; white-space: nowrap; padding-top: 5px;text-align: left;padding-left: 2px;">' . $userData['first_name'] . ' ' . $userData['last_name'] . ' sent the following feedback: </div>';
        $text .= '<br />';
        $text .= '<table cellpadding="2" cellspacing="0" border="0">';
            $text .= '<tr>';
                $text .= '<td><b>Likes:</b></td>';
                $text .= '<td>' . $like . '</td>';
            $text .= '</tr>';
            $text .= '<tr>';
                $text .= '<td><b>To be improved:</b></td>';
                $text .= '<td>' . $improve . '</td>';
            $text .= '</tr>';
            $text .= '<tr>';
                $text .= '<td><b>New features:</b></td>';
                $text .= '<td>' . $newFeatures . '</td>';
            $text .= '</tr>';
            $text .= '<tr>';
                $text .= '<td><b>Overall experience:</b></td>';
                $text .= '<td>' . $experience . '</td>';
            $text .= '</tr>';

        $text .= '</table>';

        $text .= '<div>User giving feedback: </div>';
        $text .= '<div>Email: ' . $userData['email'] . '</div>';
        $text .= '<div>Client ID: ' . $userData['client_id'] . '</div>';
        $text .= '<div>Username: ' . $userData['username'] . '</div>';

        $text .= Email::getEmailFooter();

        if (strpos($_SERVER['HTTP_HOST'], 'localhost') === false) {
            $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
            $mailer = Swift_Mailer::newInstance($transport);
            $message = Swift_Message::newInstance('Feedback - Ubirimi.com')
                ->setFrom(array('no-reply@ubirimi.com'))
                ->setTo(array('domnulnopcea@gmail.com', 'domnuprofesor@gmail.com'))
                ->setBody($text, 'text/html');

            $mailer->send($message);
        }
    }

    public static function shareIssue($clientId, $issue, $userThatShares, $userToSendEmailAddress, $noteContent) {
        if (Email::$smtpSettings) {
            $subject = Email::$smtpSettings['email_prefix'] . ' ' .
                $userThatShares['first_name'] . ' ' .
                $userThatShares['last_name'] . ' shared ' .
                $issue['project_code'] . '-' . $issue['nr'] . ': ' . substr($issue['summary'], 0, 20) . ' with you';

            $date = Util::getServerCurrentDateTime();

            EmailQueue::add($clientId,
                Email::$smtpSettings['from_address'],
                $userToSendEmailAddress,
                null,
                $subject,
                Util::getTemplate('_issueShare.php', array(
                        'issue' => $issue,
                        'userThatShares' => $userThatShares,
                        'noteContent' => $noteContent,
                        'clientDomain' => Util::getSubdomain())
                ),
                $date);
        }
    }

    public static function shareCalendar($clientId, $calendar, $userThatShares, $userToSendEmailAddress, $noteContent) {
        if (Email::$smtpSettings) {
            $subject = Email::$smtpSettings['email_prefix'] . ' ' .
                $userThatShares['first_name'] . ' ' .
                $userThatShares['last_name'] . ' shared calendar ' .
                $calendar['name'] . ' with you';

            $date = Util::getServerCurrentDateTime();

            EmailQueue::add($clientId,
                Email::$smtpSettings['from_address'],
                $userToSendEmailAddress,
                null,
                $subject,
                Util::getTemplate('_share.php', array('calendar' => $calendar,
                    'userThatShares' => $userThatShares,
                    'noteContent' => $noteContent,
                    'clientDomain' => Util::getSubdomain())),
                $date);
        }
    }

    public static function shareEvent($clientId, $event, $userThatShares, $userToSendEmailAddress, $noteContent) {
        if (Email::$smtpSettings) {
            $subject = Email::$smtpSettings['email_prefix'] . ' ' .
                $userThatShares['first_name'] . ' ' .
                $userThatShares['last_name'] . ' shared event ' .
                $event['name'] . ' with you';

            $date = Util::getServerCurrentDateTime();

            EmailQueue::add($clientId,
                Email::$smtpSettings['from_address'],
                $userToSendEmailAddress,
                null,
                $subject,
                Util::getTemplate('_eventShare.php', array('event' => $event,
                    'userThatShares' => $userThatShares,
                    'noteContent' => $noteContent,
                    'clientDomain' => Util::getSubdomain())),
                $date);
        }
    }
}