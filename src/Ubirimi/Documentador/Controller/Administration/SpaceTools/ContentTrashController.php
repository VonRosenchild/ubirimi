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
$clientSettings = \Ubirimi\Repository\$this->getRepository('ubirimi.general.client')->getSettings($clientId);

$deletedPages = Space::getDeletedPages($spaceId);

require_once __DIR__ . '/../../../Resources/views/administration/spacetools/ContentTrash.php';