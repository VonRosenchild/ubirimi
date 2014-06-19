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
}

function installMovidiusClient()
{
    $clientId = Client::create(
        'Movidius',
        'movidius',
        'http://movidius.ubirimi_net.lan',
        'contact@movidius.ro',
        Client::INSTANCE_TYPE_ON_DEMAND,
        Util::getCurrentDateTime()
    );

    // create the user
    $userId = User::createAdministratorUser(
        'Vali',
        'Muresan',
        'vali',
        'vali',
        'vali@vali.com',
        $clientId,
        20, 1, 1,
        Util::getCurrentDateTime()
    );

    $columns = 'code#summary#priority#status#created#type#updated#reporter#assignee';
    User::updateDisplayColumns($userId, $columns);

    Client::install($clientId);

    return array($clientId, $userId);
}