<?php

    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $projectId = $_GET['id'];
    $project = Project::getById($projectId);
    if ($project['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    if (isset($_POST['associate'])) {

        $notificationSchemeId = $_POST['perm_scheme'];

        Project::updateNotificationScheme($projectId, $notificationSchemeId);

        header('Location: /yongo/administration/project/notifications/' . $projectId);
    }

    $notificationSchemes = NotificationScheme::getByClientId($clientId);

    $menuSelectedCategory = 'project';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Select Project Notification Scheme';

    require_once __DIR__ . '/../../../../Resources/views/administration/project/notification_scheme/Select.php';