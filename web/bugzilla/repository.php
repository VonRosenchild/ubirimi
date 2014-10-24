<?php


use Ubirimi\Util;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Repository\Email\EmailQueue;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;
use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;
use Ubirimi\Yongo\Repository\Permission\PermissionScheme;
use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
use Ubirimi\Yongo\Repository\Project\ProjectCategory;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\SvnHosting\Repository\SvnRepository;
use Ubirimi\Calendar\Repository\Calendar;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;



use Ubirimi\Repository\User\UbirimiUser as UserRepository;
use Ubirimi\SystemProduct;
use Ubirimi\Repository\SMTPServer;
use Ubirimi\Container\UbirimiContainer;

function getUbirimiStatuses($clientId)
{
    $query = 'SELECT *
                FROM issue_status
                where client_id = ' . $clientId;

    $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getUbirimiPriorities($clientId)
{
    $query = 'SELECT *
                FROM issue_priority
                where client_id = ' . $clientId;

    $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getUbirimiIssues()
{
    $query = 'SELECT *
                FROM yongo_issue
                ORDER BY id DESC';

    $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function updateUbirimiBugSeverity($issue_id, $field_id, $value)
{
    $query = "INSERT INTO issue_custom_field_data(issue_id, field_id, value, date_created)
                    VALUES ('" . $issue_id. "', '" . $field_id . "', '" . $value . "', NOW())";

    UbirimiContainer::get()['db.connection']->query($query);

    return UbirimiContainer::get()['db.connection']->insert_id;
}

function updateUbirimiBugOS($issue_id, $field_id, $value)
{
    $query = "INSERT INTO issue_custom_field_data(issue_id, field_id, value, date_created)
                    VALUES ('" . $issue_id. "', '" . $field_id . "', '" . $value . "', NOW())";

    UbirimiContainer::get()['db.connection']->query($query);

    return UbirimiContainer::get()['db.connection']->insert_id;
}

function getUbirimiIssueIdBasedOnBugzillaBug($shortDesc, $creationTs)
{
    $query = 'SELECT *
                FROM yongo_issue
                WHERE summary = ?
                  AND date_created = ?
                LIMIT 1';

    if ($stmt = UbirimiContainer::get()['db.connection']->prepare($query)) {
        $stmt->bind_param("ss", $shortDesc, $creationTs);
        $stmt->execute();
        $result = $stmt->get_result();

        if (1 !== $result->num_rows) {
            echo "\nCould not find Ubirimi issue correspondence\n";
            die();
        }

        return $result->fetch_array(MYSQLI_ASSOC);
    }

    return null;
}

function getUbirimiIssueIdBasedOnBugzillaBug2($shortDesc, $creationTs)
{
    $query = 'SELECT *
                FROM yongo_issue
                WHERE summary = ?
                  AND date_created = ?
                LIMIT 1';

    $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
    $stmt->bind_param("ss", $shortDesc, $creationTs);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_array(MYSQLI_ASSOC);
}

function getUbirimiFieldValue($severityConstants, $severityText)
{
    foreach ($severityConstants as $key => $value) {
        if ($severityText == $value) {
            return $key;
        }
    }

    return null;
}

function getYongoStatusId($ubirimiStatuses, $status)
{
    foreach ($ubirimiStatuses as $ubirimiStatus) {
        if ($status == $ubirimiStatus['name']) {
            return $ubirimiStatus['id'];
        }
    }

    return null;
}

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
    $query = 'SELECT components.*, products.name product_name
                FROM components
              LEFT JOIN products on products.id = components.product_id
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

function getComments($connection, $bugId)
{
    $query = "SELECT longdescs.*
                FROM longdescs
                WHERE bug_id = " . $bugId . "
                ORDER BY bug_when ASC";

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

function updateIssueResolution($resolutionId, $issueId)
{
    $query = "UPDATE yongo_issue
                SET resolution_id = ?
                WHERE id = ?";

    $stmt = UbirimiContainer::get()['db.connection']->prepare($query);
    $stmt->bind_param("ii", $resolutionId, $issueId);
    $stmt->execute();
}

function installComponent($valiId, $projectId, $name, $description)
{
    YongoProject::addComponent($projectId, $name, $description, $valiId, null, Util::getServerCurrentDateTime());
}

function installVersion($projectId, $name, $description = null)
{
    YongoProject::addVersion($projectId, $name, $description, Util::getServerCurrentDateTime());
}

function getYongoProjectFromMovidusProject($movidiuProjects, $ubirimiProjects, $productId)
{
    foreach ($movidiuProjects as $movidiusProject) {
        if ($productId == $movidiusProject['id']) {
            foreach ($ubirimiProjects as $ubirimiProject) {
                if ($ubirimiProject['name'] == $movidiusProject['name']) {
                    return $ubirimiProject['id'];
                }
            }
        }
    }

    return null;
}

function getYongoUserFromMovidiusUsers($movidiusUsers, $ubirimiUsers, $userId)
{
    foreach ($movidiusUsers as $movidiusUser) {
        if ($userId == $movidiusUser['userid']) {
            foreach ($ubirimiUsers as $ubirimiUser) {
                if ($ubirimiUser['email'] == $movidiusUser['login_name']) {
                    return $ubirimiUser['id'];
                }
            }
        }
    }

    return null;
}

function getYongoPriorityFromMovidiusPriority($ubirimiPriorities, $priority)
{
    foreach ($ubirimiPriorities as $prio) {
        if ($priority == $prio['name']) {
            return $prio['id'];
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
    $permissionScheme = WorkflowScheme::getByClientId($clientId);
    $notificationScheme = WorkflowScheme::getByClientId($clientId);
    $projectCategories = ProjectCategory::getAll($clientId);

    $currentDate = Util::getServerCurrentDateTime();

    $projectId = YongoProject::add(
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

    YongoProject::addDefaultUsers($clientId, $projectId, $currentDate);
    YongoProject::addDefaultGroups($clientId, $projectId, $currentDate);

    return $projectId;
}

function dropAllTables()
{
    $comm1 = "mysqldump -u root -p12wqasxz -h '127.0.0.1' --no-data yongo | grep ^DROP > drop.sql";
    $comm2 = "mysql -u root -p12wqasxz -h '127.0.0.1' yongo < drop.sql";
    $comm3 = "rm drop.sql";

    shell_exec($comm1);
    shell_exec($comm2);
    shell_exec($comm3);
}

function insertMovidiusDatabase()
{
    $command = "mysql -uroot -p12wqasxz -h '127.0.0.1' -D yongo < ./bugzilla/Movidius.sql";

    shell_exec($command);
}

function installUser($data)
{
    $currentDate = Util::getServerCurrentDateTime();

    $issuesPerPage = $this->getRepository(UbirimiClient::class)->getYongoSetting($data['clientId'], 'issues_per_page');

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

    $result = UserRepository::add($data['clientId'], $data['firstName'], $data['lastName'], $data['email'], $data['username'], $data['password'], $issuesPerPage, $data['customer_service_desk_flag'], null, $currentDate);

    $userId = $result[0];

    $defaultColumns = 'code#summary#priority#status#created#type#updated#reporter#assignee';
    UserRepository::updateDisplayColumns($userId, $defaultColumns);

    // add default calendar
    $calendarId = Calendar::save($userId, $data['firstName'] . ' ' . $data['lastName'], 'My default calendar', '#A1FF9E', $currentDate, 1);

    if (!$data['isCustomer']) {
        // add default reminders
        Calendar::addReminder($calendarId, CalendarReminderType::REMINDER_EMAIL, Period::PERIOD_MINUTE, 30);

        // add the newly created user to the Ubirimi Users Global Permission Groups
        $groups = GlobalPermission::getDataByPermissionId($data['clientId'], GlobalPermission::GLOBAL_PERMISSION_YONGO_USERS);
        while ($groups && $group = $groups->fetch_array(MYSQLI_ASSOC)) {
            $this->getRepository(UbirimiGroup::class)->addData($group['id'], array($userId), $currentDate);
        }
    }

    if (isset($data['svnRepoId'])) {
        /* also add user to svn_repository_user table */
        SvnRepository::addUser($data['svnRepoId'], $userId);

    }

    return $userId;
}

function saveIssue($data)
{

}