<?php
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $menuSelectedCategory = 'doc_spaces';

    $spaceId = $_GET['id'];
    $space = Space::getById($spaceId);

    if ($space['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    require_once __DIR__ . '/../../../Resources/views/administration/spacetools/Overview.php';