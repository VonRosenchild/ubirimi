<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Notification\NotificationScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $notificationSchemes = NotificationScheme::getByClientId($clientId);
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Issue Notification Schemes';

    require_once __DIR__ . '/../../../Resources/views/administration/notification_scheme/List.php';