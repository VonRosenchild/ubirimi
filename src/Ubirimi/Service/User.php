<?php

namespace Ubirimi\Service;

use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Calendar\Repository\CalendarEventReminderPeriod;
use Ubirimi\Calendar\Repository\CalendarReminderType;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Event\UbirimiEvents;
use Ubirimi\Event\UserEvent;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\Group\Group;
use Ubirimi\Repository\User\User as UserRepository;
use ubirimi\svn\SVNRepository;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

class User extends UbirimiService
{
    public function newUser($data)
    {
        $currentDate = Util::getCurrentDateTime($this->session->get('client/settings/timezone'));

        $issuesPerPage = Client::getYongoSetting($data['clientId'], 'issues_per_page');

        if (array_key_exists('isCustomer', $data) && $data['isCustomer']) {
            $data['customer_service_desk_flag'] = 1;
        } else {
            $data['customer_service_desk_flag'] = 0;
            $data['isCustomer'] = 0;
        }

        if (!array_key_exists('username', $data)) {
            $data['username'] = null;
        }

        if (!array_key_exists('password', $data)) {
            $data['password'] = null;
        }

        if (!array_key_exists('country', $data)) {
            $data['country'] = null;
        }

        $result = UserRepository::add($data['clientId'], $data['firstName'], $data['lastName'], $data['email'],
                                      $data['username'], $data['password'], $issuesPerPage,
                                      $data['customer_service_desk_flag'], $data['country'], $currentDate);

        $userId = $result[0];

        $defaultColumns = 'code#summary#priority#status#created#type#updated#reporter#assignee';
        UserRepository::updateDisplayColumns($userId, $defaultColumns);

        // add default calendar
        $calendarId = Calendar::save($userId, $data['firstName'] . ' ' . $data['lastName'], 'My default calendar', '#A1FF9E', $currentDate, 1);

        if (!$data['isCustomer']) {
            // add default reminders
            Calendar::addReminder($calendarId, CalendarReminderType::REMINDER_EMAIL, CalendarEventReminderPeriod::PERIOD_MINUTE, 30);

            // add the newly created user to the Ubirimi Users Global Permission Groups
            $groups = GlobalPermission::getDataByPermissionId($this->session->get('client/id'), GlobalPermission::GLOBAL_PERMISSION_YONGO_USERS);
            while ($groups && $group = $groups->fetch_array(MYSQLI_ASSOC)) {
                Group::addData($group['id'], array($userId), $currentDate);
            }
        }

        if (isset($data['svnRepoId'])) {
            /* also add user to svn_repository_user table */
            SVNRepository::addUser($data['svnRepoId'], $userId);

            $userEvent = new UserEvent(UserEvent::STATUS_NEW_SVN, $data['firstName'], $data['lastName'], $data['username'], null, $data['email'], array('repositoryName' => $data['svnRepositoryName']));
            UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::USER, $userEvent);
        }

        $userEvent = new UserEvent(
            UserEvent::STATUS_NEW,
            $data['firstName'],
            $data['lastName'],
            $data['username'],
            $data['password'],
            $data['email'],
            array(
                'clientDomain' => $data['clientDomain'],
                'isCustomer' => $data['isCustomer'],
                'clientId' => $data['clientId']
            )
        );

//        $logEvent = new LogEvent(SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS, 'ADD User ' . $data['username']);

//        UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::LOG, $logEvent);
        UbirimiContainer::get()['dispatcher']->dispatch(UbirimiEvents::USER, $userEvent);

        return $userId;
    }
}
