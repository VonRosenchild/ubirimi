<?php
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueEvent;
    use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
    use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];
    $project = Project::getById($projectId);

    if ($project['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $notificationSchemeId = $project['notification_scheme_id'];
    $notificationScheme = NotificationScheme::getMetaDataById($notificationSchemeId);
    $events = IssueEvent::getByClient($clientId);
    $hasGlobalAdministrationPermission = User::hasGlobalPermission($clientId,
                                                                   $loggedInUserId,
                                                                   GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
    $hasGlobalSystemAdministrationPermission = User::hasGlobalPermission($clientId,
                                                                         $loggedInUserId,
                                                                         GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);
    $menuSelectedCategory = 'project';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Project Notification Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/project/notification_scheme/View.php';