<?php

    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueLinkType;

    Util::checkUserIsLoggedInAndRedirect();

    $sourceLinkTypeId = $_POST['id'];
    $targetLinkTypeId = isset($_POST['new_id']) ? $_POST['new_id'] : null;
    $action = isset($_POST['action']) ? $_POST['action'] : null;

    if ($action == 'swap') {
        IssueLinkType::updateLinkTypeId($sourceLinkTypeId, $targetLinkTypeId);
        IssueLinkType::deleteById($sourceLinkTypeId);
    } else if ($action == 'remove' || $action == null) {
        IssueLinkType::deleteLinksByLinkTypeId($sourceLinkTypeId);
        IssueLinkType::deleteById($sourceLinkTypeId);
    }