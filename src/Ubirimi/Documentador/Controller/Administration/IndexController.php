<?php
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'doc_administration';

    $spacesWithAdminPermission = Space::getWithAdminPermissionByUserId($clientId, $loggedInUserId);

    $hasDocumentatorGlobalAdministrationPermission = $session->get('user/documentator/is_global_administrator');
    $hasDocumentatorGlobalSystemAdministrationPermission = $session->get('user/documentator/is_global_system_administrator');

    require_once __DIR__ . '/../../Resources/views/administration/Index.php';