<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $groupId = $_GET['id'];
    $group = Group::getMetadataById($groupId);

    require_once __DIR__ . '/../../../Resources/views/administration/group/DeleteConfirm.php';