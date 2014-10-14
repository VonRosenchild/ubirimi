<?php


    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'doc_spaces';

    $spaceId = $_GET['id'];
    $space = Space::getById($spaceId);

    if ($space['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $documentatorSettings = $this->getRepository('ubirimi.general.client')->getDocumentatorSettings($clientId);
    $anonymousAccessSettings = Space::getAnonymousAccessSettings($spaceId);

    $usersWithPermissionForSpace = Space::getUsersWithPermissions($spaceId);
    $groupsWithPermissionForSpace = Space::getGroupsWithPermissions($spaceId);

    require_once __DIR__ . '/../../../Resources/views/administration/spacetools/Permission.php';