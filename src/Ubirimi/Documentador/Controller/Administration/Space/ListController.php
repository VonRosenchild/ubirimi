<?php
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $hasDocumentatorGlobalAdministrationPermission = $session->get('user/documentator/is_global_administrator');
    $hasDocumentatorGlobalSystemAdministrationPermission = $session->get('user/documentator/is_global_system_administrator');

    if ($hasDocumentatorGlobalAdministrationPermission || $hasDocumentatorGlobalSystemAdministrationPermission) {
        $spaces = Space::getByClientId($clientId);
    } else {
        $spaces = Space::getWithAdminPermissionByUserId($clientId, $loggedInUserId);
    }
    $clientSettings = $session->get('client/settings');

    $menuSelectedCategory = 'doc_spaces';

    require_once __DIR__ . '/../../../Resources/views/administration/space/List.php';