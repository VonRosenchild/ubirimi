<?php

use Ubirimi\Repository\Client;
use Ubirimi\Util;
use Ubirimi\Repository\User\User;
use Ubirimi\Repository\Email\EmailQueue;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;
use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
use Ubirimi\Yongo\Repository\Project\ProjectCategory;
use Ubirimi\Yongo\Repository\Project\Project;
use ubirimi\svn\SVNRepository;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Calendar\Repository\CalendarReminderType;
use Ubirimi\Repository\Group\Group;
use Ubirimi\Calendar\Repository\CalendarEventReminderPeriod;
use Ubirimi\Repository\User\User as UserRepository;
use Ubirimi\SystemProduct;
use Ubirimi\Repository\SMTPServer;

function getProducts($connection)
{
    $query = 'SELECT *
                FROM products
                ORDER BY id DESC';

    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getComponents($connection)
{
    $query = 'SELECT *
                FROM components
                ORDER BY id DESC';

    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getVersions($connection)
{
    $query = 'SELECT *
                FROM versions
                ORDER BY id DESC';

    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getUsers($connection)
{
    $query = 'SELECT *
                FROM profiles
                ORDER BY userid DESC';

    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getBugs($connection)
{
    $query = 'SELECT *
                FROM bugs
                ORDER BY bug_id DESC';

    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getPriorities($connection)
{
    $query = 'SELECT *
                FROM priority
                ORDER BY id ASC';

    $stmt = $connection->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function installComponent($valiId, $projectId, $name, $description)
{
    Project::addComponent($projectId, $name, $description, $valiId, null, Util::getCurrentDateTime());
}

function installVersion($projectId, $name, $description = null)
{
    Project::addVersion($projectId, $name, $description, Util::getCurrentDateTime());
}

function getYongoProjectFromMovidusProject($movidiuProjects, $productId)
{
    foreach ($movidiuProjects as $movidiusProject) {
        if ($productId == $movidiusProject['id']) {
            return $movidiusProject['yongo_project_id'];
        }
    }

    return null;
}

function installProject($clientId, $leadId, $name, $description)
{
    $issueTypeScheme = IssueTypeScheme::getByClientId($clientId, 'project');
    $issueTypeScreenScheme = IssueTypeScreenScheme::getByClientId($clientId);
    $fieldConfigurationSchemes = FieldConfigurationScheme::getByClient($clientId);
    $workflowScheme = WorkflowScheme::getMetaDataByClientId($clientId);
    $permissionScheme = PermissionScheme::getByClientId($clientId);
    $notificationScheme = NotificationScheme::getByClientId($clientId);
    $projectCategories = ProjectCategory::getAll($clientId);

    $currentDate = Util::getCurrentDateTime();

    $projectId = Project::add(
        $clientId,
        $issueTypeScheme->fetch_array(MYSQLI_ASSOC)['id'],
        $issueTypeScreenScheme->fetch_array(MYSQLI_ASSOC)['id'],
        $fieldConfigurationSchemes->fetch_array(MYSQLI_ASSOC)['id'],
        $workflowScheme->fetch_array(MYSQLI_ASSOC)['id'],
        $permissionScheme->fetch_array(MYSQLI_ASSOC)['id'],
        $notificationScheme->fetch_array(MYSQLI_ASSOC)['id'],
        $leadId,
        $name,
        'code',
        $description,
        null,
        0,
        $currentDate
    );

    Project::addDefaultUsers($clientId, $projectId, $currentDate);
    Project::addDefaultGroups($clientId, $projectId, $currentDate);

    return $projectId;
}

function installMovidiusClient($clientData, $valentinData)
{
    $clientId = Client::create(
        $clientData['companyName'],
        $clientData['companyDomain'],
        $clientData['baseURL'],
        $clientData['companyEmail'],
        Client::INSTANCE_TYPE_ON_DEMAND,
        Util::getCurrentDateTime()
    );

    // create the user
    $userId = User::createAdministratorUser(
        $valentinData['firstName'],
        $valentinData['lastName'],
        $valentinData['username'],
        $valentinData['password'],
        $valentinData['email'],
        $clientId,
        20, 1, 1,
        Util::getCurrentDateTime()
    );

    $columns = 'code#summary#priority#status#created#type#updated#reporter#assignee';
    User::updateDisplayColumns($userId, $columns);

    $clientData = Client::getById($clientId);
    $userData = Client::getUsers($clientId);
    $user = $userData->fetch_array(MYSQLI_ASSOC);
    $userId = $user['id'];

    $clientCreatedDate = $clientData['date_created'];

    Client::installYongoProduct($clientId, $userId, $clientCreatedDate);
    Client::installDocumentatorProduct($clientId, $userId, $clientCreatedDate);
    Client::installCalendarProduct($clientId, $userId, $clientCreatedDate);

    Client::addProduct($clientId, SystemProduct::SYS_PRODUCT_YONGO, $clientCreatedDate);
    Client::addProduct($clientId, SystemProduct::SYS_PRODUCT_CHEETAH, $clientCreatedDate);
    Client::addProduct($clientId, SystemProduct::SYS_PRODUCT_SVN_HOSTING, $clientCreatedDate);
    Client::addProduct($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $clientCreatedDate);
    Client::addProduct($clientId, SystemProduct::SYS_PRODUCT_CALENDAR, $clientCreatedDate);

    SMTPServer::add(
        $clientId,
        'Ubirimi Mail Server',
        'The default Ubirimi mail server',
        'notification@ubirimi.com',
        'UBR',
        SMTPServer::PROTOCOL_SECURE_SMTP,
        'smtp.gmail.com',
        587,
        10000,
        1,
        'notification@ubirimi.com',
        'cristinasinaomi1',
        1,
        $clientCreatedDate
    );

    Client::setInstalledFlag($clientId, 1);

    return array($clientId, $userId);
}

function installUser($data)
{
    $currentDate = Util::getCurrentDateTime();

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

    $result = UserRepository::add($data['clientId'], $data['firstName'], $data['lastName'], $data['email'], $data['username'], $data['password'], $issuesPerPage, $data['customer_service_desk_flag'], $currentDate);

    $userId = $result[0];

    $defaultColumns = 'code#summary#priority#status#created#type#updated#reporter#assignee';
    UserRepository::updateDisplayColumns($userId, $defaultColumns);

    // add default calendar
    $calendarId = Calendar::save($userId, $data['firstName'] . ' ' . $data['lastName'], 'My default calendar', '#A1FF9E', $currentDate, 1);

    if (!$data['isCustomer']) {
        // add default reminders
        Calendar::addReminder($calendarId, CalendarReminderType::REMINDER_EMAIL, CalendarEventReminderPeriod::PERIOD_MINUTE, 30);

        // add the newly created user to the Ubirimi Users Global Permission Groups
        $groups = GlobalPermission::getDataByPermissionId($data['clientId'], GlobalPermission::GLOBAL_PERMISSION_YONGO_USERS);
        while ($groups && $group = $groups->fetch_array(MYSQLI_ASSOC)) {
            Group::addData($group['id'], array($userId), $currentDate);
        }
    }

    if (isset($data['svnRepoId'])) {
        /* also add user to svn_repository_user table */
        SVNRepository::addUser($data['svnRepoId'], $userId);

    }

    return $userId;
}

function saveIssue($data)
{

}