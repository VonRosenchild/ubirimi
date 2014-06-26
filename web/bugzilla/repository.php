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
use Ubirimi\Container\UbirimiContainer;

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
    $query = 'SELECT bugs.*, bugs_fulltext.short_desc
                FROM bugs
                LEFT JOIN bugs_fulltext ON bugs.bug_id = bugs_fulltext.bug_id
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

function getYongoUserFromMovidiusUsers($movidiusUsers, $userId)
{
    foreach ($movidiusUsers as $movidiusUser) {
        if ($userId == $movidiusUser['userid']) {
            return $movidiusUser['yongo_user_id'];
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

function dropAllTables()
{
    $query = 'DROP TABLE `agile_board`, `agile_board_column`, `agile_board_column_status`, `agile_board_project`,
    `agile_board_sprint`, `agile_board_sprint_issue`, `cal_calendar`, `cal_calendar_default_reminder`,
    `cal_calendar_share`, `cal_event`, `cal_event_reminder`, `cal_event_reminder_period`, `cal_event_reminder_type`,
    `cal_event_repeat`, `cal_event_repeat_cycle`, `cal_event_share`, `client`, `client_documentator_settings`,
    `client_product`, `client_settings`, `client_smtp_settings`, `client_yongo_settings`, `documentator_entity`,
    `documentator_entity_attachment`, `documentator_entity_attachment_revision`, `documentator_entity_comment`,
    `documentator_entity_file`, `documentator_entity_file_revision`, `documentator_entity_revision`,
    `documentator_entity_snapshot`, `documentator_entity_type`, `documentator_space`, `documentator_space_permission`,
    `documentator_space_permission_anonymous`, `documentator_user_entity_favourite`, `documentator_user_space_favourite`,
    `event`, `field`, `field_configuration`, `field_configuration_data`, `field_issue_type_data`, `field_project_data`,
    `filter`, `general_invoice`, `general_log`, `general_mail_queue`, `general_payment`, `general_task_queue`,
    `group`, `group_data`, `help_customer`, `help_filter`, `help_organization`, `help_organization_user`,
    `help_reset_password`, `help_sla`, `help_sla_goal`, `issue_attachment`, `issue_comment`, `issue_component`,
    `issue_custom_field_data`, `issue_history`, `issue_link`, `issue_link_type`, `issue_priority`, `issue_resolution`,
    `issue_security_scheme`, `issue_security_scheme_level`, `issue_security_scheme_level_data`, `issue_status`,
    `issue_type`, `issue_type_field_configuration`, `issue_type_field_configuration_data`, `issue_type_scheme`,
    `issue_type_scheme_data`, `issue_type_screen_scheme`, `issue_type_screen_scheme_data`, `issue_version`,
    `issue_work_log`, `newsletter`, `notification_scheme`, `notification_scheme_data`, `permission_role`,
     `permission_role_data`, `permission_scheme`, `permission_scheme_data`, `project`, `project_category`,
     `project_component`, `project_role_data`, `project_version`, `screen`, `screen_data`, `screen_scheme`,
     `screen_scheme_data`, `server_settings`, `svn_repository`, `svn_repository_user`, `sys_condition`, `sys_country`,
     `sys_field_type`, `sys_operation`, `sys_permission`, `sys_permission_category`, `sys_permission_global`,
     `sys_permission_global_data`, `sys_product`, `sys_product_release`, `sys_workflow_post_function`,
     `sys_workflow_step_property`, `user`, `workflow`, `workflow_condition_data`, `workflow_data`,
     `workflow_position`, `workflow_post_function_data`, `workflow_scheme`, `workflow_scheme_data`,
     `workflow_step`, `workflow_step_property`, `yongo_issue`, `yongo_issue_sla`, `yongo_issue_watch`;';

    UbirimiContainer::get()['db.connection']->query($query);
}

function insertMovidiusDatabase()
{
    $query = file_get_contents(__DIR__ . '/Movidius.sql');

    UbirimiContainer::get()['db.connection']->query($query);
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