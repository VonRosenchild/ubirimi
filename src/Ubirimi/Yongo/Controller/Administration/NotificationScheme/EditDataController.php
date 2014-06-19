<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueEvent;
    use Ubirimi\Yongo\Repository\Notification\NotificationScheme;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();
    $notificationSchemeId = $_GET['id'];
    $backLink = isset($_GET['back']) ? $_GET['back'] : null;
    $projectId = isset($_GET['project_id']) ? $_GET['project_id'] : null;

    $notificationScheme = NotificationScheme::getMetaDataById($notificationSchemeId);
    if ($notificationScheme['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    if ($projectId) {
        $project = Project::getById($projectId);
        if ($project['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }
    }

    $events = IssueEvent::getByClient($clientId);
    $menuSelectedCategory = 'issue';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Notification Scheme';

    require_once __DIR__ . '/../../../Resources/views/administration/notification_scheme/EditData.php';